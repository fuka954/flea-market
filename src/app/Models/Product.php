<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'brand',
        'description',
        'image',
        'condition_id',
        'price',
        'shipping_post_code',
        'shipping_address',
        'shipping_building',
        'sold_flag',
        'sell_user',
        'buy_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function favoritedByProfiles()
    {
        return $this->belongsToMany(Profile::class, 'favorites', 'product_id', 'profile_id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'product_comment');
    }

    public function scopeSearch($query, $filters)
    {
        if (!empty($filters['search-text'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search-text'] . '%');
            });
        }

        return $query;
    }
}
