<?php

namespace App\Http\Controllers\MyCases;

use App\Data\InvoiceData;
use App\Models\PatientCase;

class GetMyCaseInvoiceData
{
    public function __invoke(PatientCase $case)
    {
        $case->loadMissing(['invoice.rows','invoice.patientCase','invoice.user']);

        return response()->json(InvoiceData::fromModel($case->invoice));
    }

}
