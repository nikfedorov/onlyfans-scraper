<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * @property string $bio_excerpt
 * @property Carbon $created_at
 */
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
     * Bio excerpt.
     *
     * @return Attribute<string, never>
     */
    public function bioExcerpt(): Attribute
    {
        return Attribute::get(fn () => Str::limit((string) $this->bio, 30));
    }

    /**
     * Get the value used to index the model.
     */
    public function getScoutKey(): mixed
    {
        return $this->username;
    }

    /**
     * Get the key name used to index the model.
     */
    public function getScoutKeyName(): mixed
    {
        return 'username';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->username,
            'username' => $this->username,
            'name' => $this->name,
            'bio' => $this->bio,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    protected function casts()
    {
        return [
            'likes' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
