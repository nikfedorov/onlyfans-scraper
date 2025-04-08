<?php

use App\Exceptions\ScrapeFailed;
use App\Jobs\ScrapeJob;
use App\Models\Account;
use Facades\App\Services\ScraperService;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
});

it('scrapes new account', function () {

    // arrange
    $account = Account::factory()
        ->withAllData()
        ->make();

    // mock
    ScraperService::shouldReceive('scrape')
        ->with($account->username)
        ->andReturn($account);

    // act
    new ScrapeJob($account->username)->handle();

    // assert
    expect(Account::first())
        ->toHaveAttributes([
            'username' => $account->username,
            'name' => $account->name,
            'likes' => $account->likes,
            'bio' => $account->bio,
        ]);
});

it('scrapes existing account', function () {

    // arrange
    $account = Account::factory()->create();
    $factoryAccount = Account::factory()
        ->withAllData()
        ->username($account->username)
        ->make();

    // mock
    ScraperService::shouldReceive('scrape')
        ->with($account->username)
        ->andReturn($factoryAccount);

    // act
    new ScrapeJob($account->username)->handle();

    // assert
    expect(Account::first())
        ->toHaveAttributes([
            'username' => $factoryAccount->username,
            'name' => $factoryAccount->name,
            'likes' => $factoryAccount->likes,
            'bio' => $factoryAccount->bio,
        ]);
});

it('fails to scrape', function () {

    // arrange
    $account = Account::factory()->make();

    // mock
    ScraperService::shouldReceive('scrape')
        ->with($account->username)
        ->andThrow(new ScrapeFailed(
            message: 'Scrape failed',
            previous: new Exception('Failed to parse JSON'),
        ));

    // act
    new ScrapeJob($account->username)->handle();

    // assert
    expect(Account::count())
        ->toBe(0);
});

it('relaunches the job', function ($likes, $delay) {

    // arrange
    $account = Account::factory()
        ->likes($likes)
        ->make();

    // mock
    $scraper = ScraperService::shouldReceive('scrape')
        ->with($account->username)
        ->andReturn($account);

    // act
    new ScrapeJob($account->username)->handle();

    // assert
    Queue::assertPushed(ScrapeJob::class, function ($job) use ($account, $delay) {
        return $job->username === $account->username
            && $job->delay->isSameMinute(now()->addHours($delay));
    });
})->with([
    'regular account' => [10, 72],
    'popular account' => [100_001, 24],
]);

it('keeps one job per account', function () {

    // arrange
    $account = Account::factory()->make();

    // act
    ScrapeJob::dispatch($account->username);
    ScrapeJob::dispatch($account->username);
    ScrapeJob::dispatch(Account::factory()->make()->username);

    // assert
    Queue::assertPushed(ScrapeJob::class, 2);
});
