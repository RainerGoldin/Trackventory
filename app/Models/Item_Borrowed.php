<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_Borrowed extends Model
{
    use HasFactory;

    protected $table = 'item_borroweds';

    protected $fillable = [
        'user_id',
        'item_id',
        'borrow_status_id',
        'quantity',
        'borrow_date',
        'return_date',
        'due_date',
        'fine'
    ];

    /**
     * Get the user that borrowed the item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the item that was borrowed
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function borrow_status()
    {
        return $this->belongsTo(Borrow_Status::class);
    }
}
