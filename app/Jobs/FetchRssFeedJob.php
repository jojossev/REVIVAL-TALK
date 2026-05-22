<?php

namespace App\Jobs;

use App\Models\RSS;
use App\Services\XMLService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRssFeedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $timeout = 10;
    public $tries = 3;
    protected $rss;

    /**
     * Create a new job instance.
     */
    public function __construct(RSS $rss)
    {
        $this->rss = $rss;
    }

    /**
     * Execute the job.
     */
    public function handle(): Collection
    {
        $items = collect();

        if (!$this->rss || !$this->rss->feed_url) {
            Log::warning('RSS feed missing or invalid', ['rss_id' => $this->rss->id ?? null]);
            return $items;
        }

        $this->fetchFeedItems($this->rss, $items);

        return $items;
    }

    /**
     * Fetch feed items from RSS source
     */
    private function fetchFeedItems(RSS $rss, Collection &$items): void
    {
        try {
            $response = Http::timeout(10)->get($rss->feed_url);

            if (!$response->successful()) {
                Log::warning('RSS request failed', ['url' => $rss->feed_url, 'rss_id' => $rss->id]);
                return;
            }

            $contentType = $response->header('Content-Type');
            if (!str_contains($contentType, 'xml')) {
                Log::warning('Invalid RSS content type', ['url' => $rss->feed_url, 'rss_id' => $rss->id]);
                return;
            }

            // Clean XML content before parsing
            $xmlContent = XMLService::cleanXmlContent($response->body());
            $xml = simplexml_load_string(
                $xmlContent,
                'SimpleXMLElement',
                LIBXML_NOCDATA
            );

            if (!isset($xml->channel->item)) {
                return;
            }

            // Load relationships
            $rss->load(['category', 'sub_category', 'language']);

            foreach ($xml->channel->item as $item) {
                $image = $this->extractImage($item);
                $pubDate = isset($item->pubDate) ? (string) $item->pubDate : null;
                $date = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');
                $publishedDate = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : null;

                $items->push([
                    'id' => null,
                    'rss_feed_id' => $rss->id,
                    'rss_feed_name' => $rss->feed_name ?? '',
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'link' => (string) $item->link,
                    'pubDate' => $pubDate,
                    'image' => $image,
                    'content_type' => 'rss_feed',
                    'content_value' => (string) $item->link,
                    'category_id' => $rss->category_id ?? 0,
                    'category_name' => $rss->category && $rss->category->category_name ? $rss->category->category_name : '',
                    'subcategory_id' => $rss->subcategory_id ?? 0,
                    'subcategory_name' => $rss->sub_category && $rss->sub_category->subcategory_name ? $rss->sub_category->subcategory_name : '',
                    'language_id' => $rss->language_id ?? 0,
                    'date' => $date,
                    'published_date' => $publishedDate,
                    'status' => 1,
                    'is_draft' => 0,
                    'is_comment' => 0,
                    'location_id' => 0,
                    'total_like' => 0,
                    'total_views' => 0,
                    'total_comments' => 0,
                    'is_bookmark' => 0,
                    'images' => [],
                ]);
            }

            // Cache individual RSS feed items for 30 minutes
            // This allows queue-dispatched jobs to also cache their results
            Cache::store(config('cache.default'))->put(
                "rss_feed_{$rss->id}",
                $items->values(),
                now()->addMinutes(30)
            );

        } catch (\Throwable $e) {
            Log::error('RSS parsing error', [
                'url' => $rss->feed_url,
                'rss_id' => $rss->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if a MIME type and/or medium attribute indicates an image.
     * Handles exact types (image/jpeg), wildcards (image/*), and medium="image".
     */
    private function isImageType(string $type, string $medium = ''): bool
    {
        if ($medium === 'image') {
            return true;
        }

        if ($type === '' || $type === 'image/*' || str_starts_with($type, 'image/')) {
            return true;
        }

        return false;
    }

    /**
     * Extract image from RSS item, trying multiple XML structures
     * in order: enclosure → media:content → media:thumbnail → <img> in description/content:encoded
     */
    private function extractImage($item): string
    {
        return $this->fromEnclosure($item)
            ?? $this->fromMediaNamespace($item)
            ?? $this->fromHtmlContent($item)
            ?? '';
    }

    private function fromEnclosure($item): ?string
    {
        if (!isset($item->enclosure)) {
            return null;
        }

        $attrs = $item->enclosure->attributes();
        $type = (string) ($attrs->type ?? '');
        $url  = (string) ($attrs->url ?? '');

        if ($url && $this->isImageType($type)) {
            return $url;
        }

        return null;
    }

    private function fromMediaNamespace($item): ?string
    {
        $namespaces = $item->getNamespaces(true);
        $mediaUri = $namespaces['media'] ?? 'http://search.yahoo.com/mrss/';

        $media = $item->children($mediaUri);

        if (isset($media->content)) {
            foreach ($media->content as $content) {
                $attrs = $content->attributes();
                $url    = (string) ($attrs->url ?? '');
                $type   = (string) ($attrs->type ?? '');
                $medium = (string) ($attrs->medium ?? '');

                if ($url && $this->isImageType($type, $medium)) {
                    return $url;
                }
            }
        }

        if (isset($media->thumbnail)) {
            $url = (string) ($media->thumbnail->attributes()->url ?? '');
            if ($url) {
                return $url;
            }
        }

        return null;
    }

    private function fromHtmlContent($item): ?string
    {
        $html = '';

        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['content'])) {
            $contentNs = $item->children($namespaces['content']);
            if (isset($contentNs->encoded)) {
                $html = (string) $contentNs->encoded;
            }
        }

        if (empty($html) && isset($item->description)) {
            $html = (string) $item->description;
        }

        if ($html && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
