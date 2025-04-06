<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Account
 */
class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The account's username.
             *
             * @example "johndoe"
             */
            'username' => $this->username,

            /**
             * The account's name.
             *
             * @example "John Doe"
             */
            'name' => $this->name,

            /**
             * Number of account's likes.
             *
             * @example 100
             */
            'likes' => $this->likes,

            /**
             * The account's bio.
             *
             * @example "Lorem ipsum dolor sit amet."
             */
            'bio' => $this->bio,
        ];
    }
}
