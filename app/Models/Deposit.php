<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['client_id', 'safe_id', 'amount', 'deposit_date', 'status', 'tenant_id'];

    protected $casts = ['deposit_date' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'reference_id')->where('type', 'deposit');
    }

    public function returnPayments()
    {
        return $this->hasMany(Payment::class, 'reference_id')->where('type', 'deposit_return');
    }
}
