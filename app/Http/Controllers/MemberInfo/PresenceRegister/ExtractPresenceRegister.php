<?php

namespace App\Http\Controllers\MemberInfo\PresenceRegister;

use App\Export\ExportPresenceOfMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ExtractPresenceRegister
{

     public function __invoke(Request $request)
     {
         abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
         try {
             $date = Carbon::createFromFormat('Y-m-d', $request->get('date', today()->format('Y-m-d')));
         } catch (\Exception $e) {
             $date = today();
         }


         $month =  $date->format('M');

        return  ((new ExportPresenceOfMember($date))->download('Mapa de efectividade do mês de ' . $month . '.xlsx', Excel::XLSX));
     }
}
