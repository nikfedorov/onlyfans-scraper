<?php

use App\Exceptions\ScrapeFailed;
use App\Jobs\ScrapeJob;
use App\Models\Account;
use Facades\App\Services\ScraperService;

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
    ScrapeJob::dispatch($account->username);

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
    ScrapeJob::dispatch($account->username);

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
    ScrapeJob::dispatch($account->username);

    // assert
    expect(Account::count())
        ->toBe(0);
});
