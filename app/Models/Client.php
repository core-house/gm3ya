<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'name', 'phone', 'address', 'rate', 'status',
        'national_id', 'work_place', 'salary',
        'guarantor_name', 'guarantor_phone', 'guarantor_national_id', 
        'guarantor_address', 'guarantor_client_id', 'tenant_id'
    ];

    public function associations()
    {
        return $this->belongsToMany(Association::class, 'association_members')
                    ->withPivot('turn_number', 'due_date', 'collection_status')
                    ->withTimestamps();
    }

    public function associationMembers()
    {
        return $this->hasMany(AssociationMember::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function guarantor()
    {
        return $this->belongsTo(Client::class, 'guarantor_client_id');
    }

    public function guaranteedClients()
    {
        return $this->hasMany(Client::class, 'guarantor_client_id');
    }
}
