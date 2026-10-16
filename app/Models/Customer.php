<?php

namespace App\Models;

use App\Enums\CustomerType;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'organization_name', 'customer_type', 'email', 'phone', 'phone_hash', 'address', 'password', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class Customer extends Authenticatable
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected string $guard_name = 'customer';

    protected static function booted(): void
    {
        static::saving(function (Customer $customer) {
            if ($customer->isDirty('phone') && ! empty($customer->phone)) {
                $customer->phone_hash = static::hashPhone((string) $customer->phone);
            }
        });
    }

    public static function hashPhone(string $phone): string
    {
        $normalized = preg_replace('/\D/', '', $phone);

        return hash('sha256', $normalized);
    }

    protected function casts(): array
    {
        return [
            'customer_type' => CustomerType::class,
            'phone' => 'encrypted',
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    public function testRequests(): HasMany
    {
        return $this->hasMany(TestRequest::class);
    }

    public function documentAccessLogs(): HasMany
    {
        return $this->hasMany(DocumentAccessLog::class);
    }
}
