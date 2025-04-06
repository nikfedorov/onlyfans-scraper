<?php

use App\Models\Account;
use Illuminate\Testing\Fluent\AssertableJson;

it('returns search results', function () {

    // arrange
    $account = Account::factory()->create()->refresh();
    Account::factory()->create()->refresh();

    // act
    $response = $this->withoutExceptionHandling()
        ->getJson(route('api.search', [
            'q' => $account->username,
        ]));

    // assert
    $response
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->count('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('username', $account->username)
                ->where('name', $account->name)
                ->where('likes', $account->likes)
                ->where('bio', $account->bio)
            )
            ->etc()
        );
});
