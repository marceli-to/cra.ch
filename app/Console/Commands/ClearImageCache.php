<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\ImageCache;

class ClearImageCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the image cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ImageCache::clearCache();
        $this->info('Image cache cleared successfully!');
    }
}
