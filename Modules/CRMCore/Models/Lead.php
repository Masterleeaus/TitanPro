<?php

namespace Modules\CRMCore\Models;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRMCore\Traits\HasCRMCode;

class Lead extends Model
{
    use HasCRMCode, HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'title',
        'description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'company_name',
        'value',
        'lead_source_id',
        'lead_status_id',
        'assigned_to_user_id',
        'converted_at',
        'converted_to_contact_id',
        'converted_to_deal_id',
        'tenant_id',
        'created_by_id',
        'updated_by_id',
        'crmcore_customer_id',
        'crmcore_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'converted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'crmcore_score' => 'integer',
    ];

    /**
     * Get the status of the lead.
     */
    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    /**
     * Alias for leadStatus relationship
     */
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    /**
     * Get the source of the lead.
     */
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    /**
     * Alias for leadSource relationship
     */
    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    /**
     * Get the user this lead is assigned to.
     */
    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Alias for assignedToUser relationship
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the contact this lead was converted into.
     */
    public function convertedToContact()
    {
        return $this->belongsTo(Contact::class, 'converted_to_contact_id');
    }

    /**
     * Alias for convertedToContact relationship
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'converted_to_contact_id');
    }

    /**
     * Get the deal this lead was converted into.
     */
    public function convertedToDeal()
    {
        // Note: Assumes a Deal model will exist.
        return $this->belongsTo(Deal::class, 'converted_to_deal_id');
    }

    /**
     * Get the company associated with this lead (if converted to contact with company)
     */
    public function company()
    {
        return $this->hasOneThrough(Company::class, Contact::class, 'id', 'id', 'converted_to_contact_id', 'company_id');
    }

    public function crmcoreCustomer()
    {
        return $this->belongsTo(Customer::class, 'crmcore_customer_id');
    }

    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    protected static function newFactory()
    {
        return \Modules\CRMCore\database\factories\LeadFactory::new();
    }

    /**
     * Get the user who created this lead.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who last updated this lead.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
