<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Request extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'request_status_id',
        'category_id',
        'invoice_id',
        'requested_by',
        'approved_by',
        'item_requested',
        'description',
        'quantity',
        'price_per_pcs',
        'request_date',
        'approved_budget',
        'used_budget'
    ];

    protected $table = 'purchase_requests';
    
    public function requestStatus()
    {
        return $this->belongsTo(Request_Status::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function invoice()
    {
        return $this->belongsTo(Purchase_Invoice::class);
    }
    
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
