<?php declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Models\Workspace;
use App\Enums\CustomerPaymentType;
use App\Enums\CustomerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

class Customer extends Model
{
    use Billable;

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',                   // required
        'name',                 // required
        // 'status',
        // 'payment_type',         // required
        'line1',                // required
        'line2',
        'unit_number',
        'city',                 // required
        'state',                // required
        'postal_code',          // required
        'country',
        'agreed_to_terms',      // required
        'agreed_to_privacy',    // required
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'agreed_to_terms' => 'boolean',
        'agreed_to_privacy' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($customer): void {
            $customer->id = (string) Str::uuid();
        });
    }

    public function getStatusAttribue()
    {
        return $this->attributes['status'] instanceof CustomerStatus ? $this->attributes['status'] : CustomerStatus::from($this->attributes['status']);
    }

    public function setStatusAttribute($value)
    {
        return $this->attributes['status'] = $value instanceof CustomerStatus ? $value : CustomerStatus::from($value);
    }

    public function getPaymentTypeAttribue()
    {
        return $this->attributes['payment_type'] instanceof CustomerPaymentType ? $this->attributes['payment_type'] : CustomerPaymentType::from($this->attributes['payment_type']);
    }

    public function setPaymentTypeAttribute($value)
    {
        return $this->attributes['payment_type'] = $value instanceof CustomerPaymentType ? $value : CustomerPaymentType::from($value);
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }

    public function scopeWithSubscriptions(Builder $builder, string $customerID): Builder
    {
        return $builder->with('subscriptions')->where('id', $customerID);
    }
}
