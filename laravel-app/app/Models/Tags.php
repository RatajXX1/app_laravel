<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'posts_tags';
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'tag_id'
    ];

    // public function Tag(): HasOne
    // {
    //     return $this->hasOne(Tag::class);
    // }

    // public function post(): MorphTo
    // {
    //     return $this->morphTo();
    // }

}
