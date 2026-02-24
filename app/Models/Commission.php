<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'association_id', 'safe_id', 'amount', 'type', 'commission_date', 'notes', 'tenant_id'
    ];

    protected $casts = ['commission_date' => 'date'];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
