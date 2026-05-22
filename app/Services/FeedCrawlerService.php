<?php
namespace App\Services;

use App\Models\FeedItem;
use App\Models\RSS;
use Vedmant\FeedReader\Facades\FeedReader;
use Illuminate\Support\Facades\Log;

class FeedCrawlerService
{
    public function crawlAll(): void
    {
        RSS::where('status', true)->each(function (RSS $source) {
            $this->crawlSource($source);
        });
    }

    public function crawlSource(RSS $source): int
    {
        try {
            $feed = FeedReader::read($source->feed_url);

            if ($feed->error()) {
                Log::warning("Feed error for [{$source->feed_name}]: " . $feed->error());
                return 0;
            }

            $rows = [];
            $now  = now()->toDateTimeString();

            foreach ($feed->get_items() as $item) {
                $guid = $item->get_id() ?: $item->get_permalink();

                if (!$guid) continue;

                $rows[] = [
                    'guid'          => $guid,
                    'rss_source_id' => $source->id,
                    'title'         => $item->get_title() ?? 'Untitled',
                    'url'           => $item->get_permalink(),
                    'description'   => $this->cleanDescription($item->get_description()),
                    'image_url'     => $this->extractImage($item),
                    'author'        => $item->get_author()?->get_name(),
                    'published_at'  => $item->get_gmdate('Y-m-d H:i:s'),
                    'fetched_at'    => $now,
                    'updated_at'    => $now,
                    'created_at'    => $now,
                ];
            }

            $count = count($rows);

            if ($count > 0) {
                // Single bulk upsert — matched on composite unique key (guid + rss_source_id)
                FeedItem::upsert(
                    $rows,
                    ['guid', 'rss_source_id'],                              // unique match columns
                    ['title', 'url', 'description', 'image_url', 'author', 'published_at', 'fetched_at', 'updated_at']
                );
            }

            $source->update(['last_fetched_at' => now()]);
            Log::info("Crawled [{$source->feed_name}]: {$count} items processed.");

            return $count;

        } catch (\Throwable $e) {
            Log::error("Failed to crawl [{$source->feed_name}]: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Strip HTML tags and decode entities from a feed description.
     */
    private function cleanDescription(?string $description): ?string
    {
        if ($description === null || $description === '') {
            return null;
        }

        // Strip all HTML tags, then decode remaining HTML entities (e.g. &amp; &nbsp;)
        return trim(html_entity_decode(strip_tags($description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Try multiple strategies to extract an image from a feed item.
     */
    private function extractImage($item): ?string
    {
        Log::info('Extracting image from item: ' . $item);
        // 1. Enclosure (podcasts / standard RSS image attachment)
        $enclosure = $item->get_enclosure();
        if ($enclosure && str_starts_with((string) $enclosure->get_type(), 'image')) {
            return $enclosure->get_link();
        }

        // 2. media:thumbnail or media:content (Yahoo Media RSS extension)
        $thumbnail = $item->get_thumbnail();
        if (!empty($thumbnail['url'])) {
            return $thumbnail['url'];
        }

        // 3. Scrape first <img> from description as fallback
        $description = $item->get_description();
        if ($description) {
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $description, $matches);
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }

        // 4. FINAL fallback -> channel/source image from the parent feed object
        $feed = $item->get_feed();
        if ($feed && method_exists($feed, 'get_image_url')) {
            $channelImage = $feed->get_image_url();
            if ($channelImage) {
                return $channelImage;
            }
        }
        return null;
    }
}
