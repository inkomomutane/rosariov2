<?php

namespace App\Http\Controllers\Members;

use App\Data\PatentData;
use App\Data\UserDto;
use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\Patent;
use App\Models\User;
use Inertia\Inertia;

class ListMembersController
{
    public function __invoke()
    {


        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');


        return Inertia::render('Members/Index', [
            'members' =>static::handle(request()->search),
            'sex' => Sex::toValues(),
            'marital_status' => MaritalStatus::toValues(),
            'patents' => PatentData::collect(Patent::all())
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {
        return UserDto::collect(
            User::query()->when($term, function ($query, $search) {
                $query->whereAny([
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
                    'addresses',
                ], 'ilike', '%'.$search.'%');
            })
                ->orderBy('created_at', 'desc')
                ->paginate(5)
                ->withQueryString()
        );
    }
}
