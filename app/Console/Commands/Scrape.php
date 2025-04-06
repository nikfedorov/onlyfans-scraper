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
            validate: ['username' => 'required|regex:/^[a-z0-9.]+$/i']
        );

        // dispatch a scrape job
        $this->info("Dispatching a job to scrape {$username}...");
        ScrapeJob::dispatch($username);

        $this->newLine();
        $this->line('Make sure you have a dev script running to process the job queue.');
        $this->line('./vendor/bin/sail composer dev');

        return Command::SUCCESS;
    }
}
