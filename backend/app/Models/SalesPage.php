<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesPage extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'slug',
        'product_description',
        'features',
        'target_audience',
        'price',
        'usp',
        'content_html',
        'status',
        'template',
        'error_message',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
