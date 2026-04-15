<?php

namespace App\Observers;

use App\Models\User;
use Random\RandomException;

class NIPGenerator
{

    public function creating(User $user): void
    {
        if (empty($user->nip)) {
            $user->nip = $user->birth_date?->format('y') . $user->ingress_date->format('y') . $this->generateNIP();
        }
    }

    public function created(User $user): void
    {
         if(!$user->nip){
             $user->nip = $user->birth_date?->format('y') . $user->ingress_date->format('y') . $this->generateNIP();
             $user->saveQuietly();
         }
    }

    private function generateNIP(): string
    {
        $row = \DB::selectOne("SELECT nextval('document_no_seq') AS n");
        return str_pad($row->n, 4, '0', STR_PAD_LEFT);
    }
}
