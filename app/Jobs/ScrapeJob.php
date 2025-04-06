<?php

namespace App\Jobs;

use App\Exceptions\ScrapeFailed;
use App\Models\Account;
use App\Services\ScraperService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScrapeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $username,
    ) {
        $this->onQueue('scrape');
    }

    /**
     * Execute the job.
     */
    public function handle(ScraperService $scraper): void
    {
        // scrape the account
        try {
            $account = $scraper->scrape($this->username);
        } catch (ScrapeFailed $e) {
            $this->fail($e);

            return;
        }

        // update or create the account in the database with the username as unique identifier
        Account::updateOrCreate(
            ['username' => $this->username],
            [
                'name' => $account->name,
                'likes' => $account->likes,
                'bio' => $account->bio,
            ]
        );
    }
}
