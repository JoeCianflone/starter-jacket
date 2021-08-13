<?php declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Customer\Customer;
use App\Domain\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Workspace extends Model
{
    use Searchable;

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',               // required
        'name',             // required
        'customer_id',      // required
        'sub',              // required
        'domain',
        'status',           // required
        'activation_token',
        'activation_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'activation_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($workspace): void {
            $workspace->id = (string) Str::uuid();
        });
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
