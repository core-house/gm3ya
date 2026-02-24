<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['client_id', 'safe_id', 'amount', 'type', 'reference_id', 'receipt_date', 'notes', 'tenant_id'];

    protected $casts = ['receipt_date' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
