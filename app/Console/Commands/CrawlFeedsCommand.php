<?php

namespace App\Console\Commands;

use App\Models\RSS;
use App\Services\FeedCrawlerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrawlFeedsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:crawl-feeds-command';
    protected $signature = 'feeds:crawl {--source_id= : Crawl a specific source by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl all active RSS feed sources and store new items';

    /**
     * Execute the console command.
     */
    public function handle(FeedCrawlerService $crawler)
    {
        Log::info('Crawling feeds command started', ['command' => $this->signature]);
         if ($id = $this->option('source_id')) {
            $source = RSS::findOrFail($id);
            $count = $crawler->crawlSource($source);
            $this->info("Crawled source [{$source->name}]: {$count} items.");
        } else {
            $this->info('Crawling all active feeds...');
            $crawler->crawlAll();
            $this->info('Done.');
        }

        return Command::SUCCESS;
    }
}
