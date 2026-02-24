<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AssociationMember extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'association_id', 'client_id', 'turn_number', 'due_date', 'collection_status',
        'insurance_amount', 'insurance_status', 'insurance_paid_date', 'tenant_id'
    ];

    protected $casts = [
        'due_date' => 'date',
        'insurance_paid_date' => 'date',
    ];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
