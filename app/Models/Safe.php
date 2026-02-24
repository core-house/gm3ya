<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['name', 'balance', 'description', 'tenant_id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
