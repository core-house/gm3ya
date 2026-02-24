<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['safe_id', 'type', 'amount', 'description', 'transaction_date', 'tenant_id'];

    protected $casts = ['transaction_date' => 'date'];

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
