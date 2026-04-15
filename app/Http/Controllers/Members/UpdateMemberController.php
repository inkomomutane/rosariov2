<?php

namespace App\Http\Controllers\Members;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\User;

class UpdateMemberController
{
    public function __invoke(User $user,UserDto $dto)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        $user->name  = $dto->name;
        $user->email = $dto->email;
        $user->nip = $dto->nip;
        $user->first_name = $dto->first_name;
        $user->second_name = $dto->second_name;
        $user->last_name = $dto->last_name;
        $user->sex = $dto->sex;
        $user->language = $dto->language;
        $user->other_languages = $dto->other_languages;
        $user->birth_date = $dto->birth_date;
        $user->place_of_birth = $dto->place_of_birth;
        $user->nationality = $dto->nationality;
        $user->marital_status = $dto->marital_status;
        $user->id_number = $dto->id_number;
        $user->id_emitted_at = $dto->id_emitted_at;
        $user->id_expires_at = $dto->id_expires_at;
        $user->passport_number = $dto->passport_number;
        $user->passport_emitted_at = $dto->passport_emitted_at;
        $user->passport_expires_at = $dto->passport_expires_at;
        $user->tax_number = $dto->tax_number;
        $user->driver_licence = $dto->driver_licence;
        $user->driver_licence_emitted_at = $dto->driver_licence_emitted_at;
        $user->driver_licence_expires_at = $dto->driver_licence_expires_at;
        $user->dir = $dto->dir;
        $user->dir_emitted_at = $dto->dir_emitted_at;
        $user->dir_expires_at = $dto->dir_expires_at;
        $user->contacts = $dto->contacts;
        $user->primary_contact = $dto->primary_contact;
        $user->emails = $dto->emails;
        $user->primary_email = $dto->primary_email;
        $user->is_admin = $dto->is_admin;
        $user->ingress_date = $dto->ingress_date;
        $user->patent_id = $dto->patent_id;
        $user->save();
        return redirect()->back()->with('messages', AlertDto::success(__('Member updated successfully')));
    }
}
