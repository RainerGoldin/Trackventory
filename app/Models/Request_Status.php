<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_Status extends Model
{
    use HasFactory;
    
    protected $table = 'request_statuses';
    
    protected $fillable = [
        'status_name',
        'badge_color'
    ];
    
    /**
     * Get the purchase requests associated with this status
     */
    public function purchaseRequests()
    {
        return $this->hasMany(Purchase_Request::class, 'request_status_id');
    }
}
