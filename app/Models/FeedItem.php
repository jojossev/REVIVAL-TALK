<?php

namespace App\Models;

use App\Traits\HasSafeOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    use HasFactory, HasSafeOrder;

    protected $fillable = [
        'rss_source_id', 'guid', 'title', 'url',
        'description', 'image_url', 'author', 'published_at', 'fetched_at'
    ];


    protected $orderable = ['rss_source_id', 'id', 'title', 'published_at', 'fetched_at'];


    protected $casts = ['published_at' => 'datetime', 'fetched_at' => 'datetime'];

    protected $hidden = ['created_at', 'updated_at'];

    // scope
    // select only few columns
    public function scopeSelectedColumns($query)
    {
        return $query->select('id', 'rss_source_id', 'title', 'url', 'description', 'image_url', 'author', 'published_at');
    }


    public function source()
    {
        return $this->belongsTo(RSS::class, 'rss_source_id');
    }
}
