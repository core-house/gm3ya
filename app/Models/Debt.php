<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['client_id', 'amount', 'type', 'due_date', 'status', 'tenant_id'];

    protected $casts = ['due_date' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id')->where('type', 'debt');
    }
}
