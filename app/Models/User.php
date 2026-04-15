<?php

namespace App\Models;

use App\Data\UserDto;
use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Observers\NIPGenerator;
use App\Observers\PasswordFiller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([NIPGenerator::class])]
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUlids, WithData, HasRoles;
    use InteractsWithMedia;

    protected $with = ['patent'];


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'email_verified_at',
        'remember_token',
        'first_name',
        'second_name',
        'last_name',
        'sex',
        'language',
        'other_languages',
        'birth_date',
        'place_of_birth',
        'nationality',
        'marital_status',
        'id_number',
        'id_emitted_at',
        'id_expires_at',
        'passport_number',
        'passport_emitted_at',
        'passport_expires_at',
        'tax_number',
        'driver_licence',
        'driver_licence_emitted_at',
        'driver_licence_expires_at',
        'dir',
        'dir_emitted_at',
        'dir_expires_at',
        'contacts',
        'primary_contact',
        'emails',
        'primary_email',
        'is_admin',
        'addresses',
        'ingress_date',
        'patent_id',
        'is_active'
    ];

    public string $dataClass = UserDto::class;


    protected static function booted(): void
    {
        static::addGlobalScope('active', static function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }





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
            'other_languages' => 'array',
            'birth_date' => 'date',
            'ingress_date' => 'date',
            'contacts' => 'array',
            'emails' => 'array',
            'sex' => Sex::class,
            'marital_status' => MaritalStatus::class,
            'driver_licence_emitted_at' => 'date',
            'driver_licence_expires_at' => 'date',
            'dir_emitted_at' => 'date',
            'dir_expires_at' => 'date',
            'id_emitted_at' => 'date',
            'id_expires_at' => 'date',
            'passport_emitted_at' => 'date',
            'passport_expires_at' => 'date',
        ];
    }

    public function getAuthIdentifierName(): string
    {
        return 'email';
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'policeman_id', 'id');
    }

    public function reintegrations(): HasMany
    {
        return $this->hasMany(Reintegration::class, 'policeman_id', 'id');
    }

    public function disciplinaryRecords(): HasMany
    {
        return $this->hasMany(DisciplinaryRecord::class, 'policeman_id', 'id');
    }

    public function presenceRegisters(): HasMany
    {
        return $this->hasMany(PresenceRegister::class, 'user_id', 'id');
    }

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patent::class);
    }

//    public function registerMediaConversions(?Media $media = null): void
//    {
//        $this->addMediaConversion('avatar')
//            ->width('500')
//            ->nonQueued();
//    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

}
