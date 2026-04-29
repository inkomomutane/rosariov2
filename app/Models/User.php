<?php

namespace App\Models;

use App\Data\UserDto;
use App\Enum\MaritalStatus;
use App\Enum\PaymentType;
use App\Enum\Sex;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Tpetry\QueryExpressions\Function\Conditional\Coalesce;
use Tpetry\QueryExpressions\Operator\Arithmetic\Subtract;
use Tpetry\QueryExpressions\Value\Value;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUlids, WithData, HasRoles;
    use InteractsWithMedia;



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public string $dataClass = UserDto::class;


    protected $appends = [
        'balance',
        'pending_amount',
        'withdraw_amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }


    public function person(): HasOne
    {
        return $this->hasOne(Person::class, 'user_id');
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function getBalanceAttribute(): string
    {
        $expression = new Subtract(
            new Coalesce(['debit', new Value(0)]),
            new Coalesce(['credit', new Value(0)])
        );
        return $this->payments()->sum($expression);
    }

    public function getPendingAmountAttribute(): string
    {
        $expression = new Subtract(
            new Coalesce(['debit', new Value(0)]),
            new Coalesce(['credit', new Value(0)])
        );
        return $this->payments()->whereType(PaymentType::Invoice)->sum($expression);
    }


    public function getWithdrawAmountAttribute(): string
    {
        $expression = new Subtract(
            new Coalesce(['debit', new Value(0)]),
            new Coalesce(['credit', new Value(0)])
        );
        return $this->payments()->whereType(PaymentType::Withdraw)->sum($expression);
    }


}
