<?php

namespace App\Console\Commands;

use App\Models\FeedItem;
use App\Models\RSS;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeOldFeedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:purge 
                                {--days=30 : Delete items older than this many days}
                                {--keep=10 : Minimum items to keep per source regardless of age}
                                {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old feed items while keeping a minimum per source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days    = (int) $this->option('days');
        $keep    = (int) $this->option('keep');
        $dryRun  = $this->option('dry-run');
        $cutoff  = now()->subDays($days);
        //

        $this->info("Purging feed items older than {$days} days (keeping min {$keep} per source)...");
        $this->info("Cutoff date: {$cutoff}");

         if ($dryRun) {
            $this->warn('DRY RUN — nothing will be deleted.');
        }

          $totalDeleted = 0;

        // Process per source so we can respect the minimum keep rule
        RSS::where('status', 1)->each(function (RSS $source) use ($cutoff, $keep, $dryRun, &$totalDeleted) {

            // Get IDs of the newest $keep items for this source — these are protected
            $protectedIds = FeedItem::where('rss_source_id', $source->id)
                ->orderBy('published_at', 'DESC')
                ->limit($keep)
                ->pluck('id');

            // Find deletable items: older than cutoff AND not in protected list
            $query = FeedItem::where('rss_source_id', $source->id)
                ->where('published_at', '<', $cutoff)
                ->when($protectedIds->isNotEmpty(), fn($q) => $q->whereNotIn('id', $protectedIds));

            $count = $query->count();

            if ($count === 0) {
                return; // nothing to delete for this source
            }

            if ($dryRun) {
                $this->line("  [DRY] Source [{$source->feed_name}] → would delete {$count} items");
            } else {
                $query->delete();
                $this->line("  Source [{$source->feed_name}] → deleted {$count} items");
                Log::info("Feed purge: deleted {$count} items from source [{$source->feed_name}] (id: {$source->id})");
            }

            $totalDeleted += $count;
        });

        $this->info($dryRun
            ? "DRY RUN complete — would delete {$totalDeleted} items total."
            : "Purge complete — {$totalDeleted} items deleted."
        );

        return Command::SUCCESS;
    }
}
