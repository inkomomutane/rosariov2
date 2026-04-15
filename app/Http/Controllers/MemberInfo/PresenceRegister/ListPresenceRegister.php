<?php

namespace App\Http\Controllers\MemberInfo\PresenceRegister;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListPresenceRegister
{
    public function __invoke(Request $request): Response
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');

        try {
            $date = Carbon::createFromFormat('Y-m-d', $request->get('date', today()->format('Y-m-d')));
        } catch (\Exception $e) {
            $date = today();
        }

        return Inertia::render('MemberPresence/MemberPresence', [
            'presences' => static::handle(request()->search, $date),
            'date' => $request->get('date', $date->format('Y-m-d')),
        ]);
    }

    public static function handle(?string $term = '', ?Carbon $date = null)
    {
        $date  =  $date ?? Carbon::now();

        //   get all users and their presence from 30 days of month provided#
        $start =  $date->copy()->startOfMonth();
        $end   = $date->copy()->endOfMonth();

        $users = User::query()->when($term, function ($query, $search) {
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
            ], 'LIKE', "%$search%");
        })->with(['presenceRegisters' => function ($query) use ($start, $end) {
            $query->whereBetween('date', [$start, $end]);
        }])->get();
        $users->each(function ($user) use ($start, $end) {
            $days = collect();
            $period = \Carbon\CarbonPeriod::create($start, $end);

            foreach ($period as $day) {
                $presence = $user->presenceRegisters->firstWhere('date', $day->toDateString());
                $days->push([
                    'day'     =>  Carbon::create($day->toDateString())->day,
                    'present'  =>  $presence ? 'P': 'F',
                    'details'  => $presence
                ]);
            }
            $user->month_presence = $days;
        });

        $data =    $users->map(function (User $user)
        {

            return     array_merge([
                'member' => $user->name,
                'NIP' => $user->nip,
            ],collect($user->month_presence)->map(function ($item) {
                return  $item['present'];
            })->values()->toArray());
        });
        return $data;
    }
}
