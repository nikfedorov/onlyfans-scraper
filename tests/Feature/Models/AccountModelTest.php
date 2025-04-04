<?php

use App\Models\Account;

it('has Account attributes', function () {

    // arrange
    $account = Account::factory()
        ->withAllData()
        ->create();

    // assert
    expect($account)
        ->toHaveAttributes([
            'username' => $account->username,
            'name' => $account->name,
            'likes' => $account->likes,
            'bio' => $account->bio,
        ]);
});
