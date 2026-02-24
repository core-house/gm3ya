<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['client_id', 'safe_id', 'amount', 'loan_date', 'status', 'tenant_id'];

    protected $casts = ['loan_date' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id')->where('type', 'loan');
    }
}
