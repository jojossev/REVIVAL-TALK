<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'tbl_token';

    protected $fillable = ['user_id', 'token', 'language_id', 'latitude', 'longitude'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
