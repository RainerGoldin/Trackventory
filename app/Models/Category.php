<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_name',
        'description'
    ];

    protected $table = 'categories';

    /**
     * Get the items for this category.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
