<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'name', 'monthly_amount', 'members_count', 'total_amount', 
        'insurance_amount', 'commission_amount', 'commission_type',
        'start_date', 'status', 'tenant_id'
    ];

    protected $casts = ['start_date' => 'date'];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'association_members')
                    ->withPivot('turn_number', 'due_date', 'collection_status')
                    ->withTimestamps();
    }

    public function members()
    {
        return $this->hasMany(AssociationMember::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id')->where('type', 'association');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'reference_id')->where('type', 'association');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}
