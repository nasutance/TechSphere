<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'post_id';
    protected $fillable = ['title', 'lead', 'content', 'author_id'];

    public function comments(): HasMany
    {
        return $this->hasMany(CommentModel::class, 'post_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
