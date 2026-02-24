<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'client_id', 'association_id', 'amount', 'type', 'reason', 
        'penalty_date', 'status', 'paid_date', 'tenant_id'
    ];

    protected $casts = [
        'penalty_date' => 'date',
        'paid_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }
}
