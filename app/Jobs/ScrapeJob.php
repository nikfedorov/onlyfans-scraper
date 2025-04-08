<?php

namespace App\Jobs;

use App\Exceptions\ScrapeFailed;
use App\Models\Account;
use Facades\App\Services\ScraperService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScrapeJob implements ShouldBeUnique, ShouldQueue
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
    public function handle(): void
    {
        // scrape the account
        try {
            $account = ScraperService::scrape($this->username);
        } catch (ScrapeFailed $e) {
            $this->fail($e);

            return;
        }

        // update or create account with username as unique identifier
        Account::updateOrCreate(
            ['username' => $this->username],
            [
                'name' => $account->name,
                'likes' => $account->likes,
                'bio' => $account->bio,
            ]
        );

        // relaunch the job
        ScrapeJob::dispatch($this->username)
            ->delay(now()->addHours($account->likes > 100_000 ? 24 : 72));
    }

    /**
     * The unique identifier for the job.
     */
    public function uniqueId(): string
    {
        return $this->username;
    }
}
