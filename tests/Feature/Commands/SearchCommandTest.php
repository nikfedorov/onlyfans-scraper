<?php

use App\Models\Account;
use Illuminate\Console\Command;

test('searches for accounts', function () {

    // arrange
    $account = Account::factory()->create();

    // act
    $result = $this->artisan('app:search')
        ->expectsQuestion('Query', $account->username)
        ->execute();

    // assert
    expect($result)->toBe(Command::SUCCESS);
});
