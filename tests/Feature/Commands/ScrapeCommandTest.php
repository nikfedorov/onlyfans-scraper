<?php

use App\Jobs\ScrapeJob;
use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

it('queues a scrape job', function () {

    // mock
    Queue::fake();

    // arrange
    $account = Account::factory()->make();

    // act
    $result = $this->artisan('app:scrape')
        ->expectsQuestion('Username', $account->username)
        ->execute();

    // assert
    expect($result)->toBe(Command::SUCCESS);

    Queue::assertPushed(ScrapeJob::class, function ($job) use ($account) {
        return $job->username === $account->username;
    });
});

it('handles existing account', function () {

    // mock
    Queue::fake();

    // arrange
    $account = Account::factory()->create();

    // act
    $result = $this->artisan('app:scrape')
        ->expectsQuestion('Username', $account->username)
        ->execute();

    // assert
    expect($result)->toBe(Command::SUCCESS);

    Queue::assertPushed(ScrapeJob::class, function ($job) use ($account) {
        return $job->username === $account->username;
    });
});
