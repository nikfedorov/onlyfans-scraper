<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeJob;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class Scrape extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:scrape';

    /**
     * The console command description.
     */
    protected $description = 'Scrape a single OnlyFans profile';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // get the username
        $username = text(
            label: 'Username',
            hint: 'Enter an OnlyFans username to scrape',
            placeholder: 'e.g. belledelphine',
            required: true
        );

        // dispatch a scrape job
        $this->info("Scraping {$username}...");
        ScrapeJob::dispatch($username);

        return Command::SUCCESS;
    }
}
