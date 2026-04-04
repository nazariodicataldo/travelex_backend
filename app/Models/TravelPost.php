<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['location', 'country', 'image', 'description', 'user_id'])]
class TravelPost extends Model
{
    use HasUuids;

    public static function booted()
    {
        static::creating(function ($post) {
            $post->slug = static::generateUniqueSlug($post->title);
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);

        $existingSlugs = static::where('slug', 'like', $baseSlug . '%')
            ->pluck('slug')
            ->toArray();

        if (!in_array($baseSlug, $existingSlugs)) {
            return $baseSlug;
        }

        do {
            $randomSuffix = Str::lower(Str::random(6));
            $newSlug = $baseSlug . '-' . $randomSuffix;
        } while (in_array($newSlug, $existingSlugs));

        return $newSlug;
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
