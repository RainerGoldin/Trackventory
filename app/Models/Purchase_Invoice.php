<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'item_purchased',
        'total_price',
        'budget',
        'quantity',
        'img',
    ];
    
    protected $table = 'purchase_invoices';
    
    protected $primaryKey = 'id';
    
    public $timestamps = true;

    /**
     * Get the purchase requests associated with this invoice.
     */
    public function purchaseRequests()
    {
        return $this->hasOne(Purchase_Request::class);
    }
}
