<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory,
        Searchable;

    public $incrementing = false;

    protected $primaryKey = 'username';

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'likes',
        'bio',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray()
    {
        return [
            'username' => (string) $this->username,
            'name' => (string) $this->name,
            'bio' => (string) $this->bio,
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
