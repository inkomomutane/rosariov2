<?php

namespace App\Http\Controllers\MyCases;

use App\Data\AlertDto;
use App\Data\PatientCaseRequestDto;
use App\Enum\PatientCaseStatus;
use App\Models\PatientCase;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UpdateMyCase
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function __invoke(PatientCase $case, PatientCaseRequestDto $dto)
     {
         $case->update([
             'priority' => $dto->priority,
             'title' => $dto->title,
             'description' => $dto->description,
             'case_type' => $dto->case_type,
             'first_review' => null,
             'final_review' => null,
             'case_date' => now(),
             'first_review_date' => null,
             'last_review_date' => null,
             'requester_doctor_id' => null,
             'status' => PatientCaseStatus::SUBMITTED,
             'assigned_doctor_id' => null,
             'last_reviewer_doctor_id' => null,
         ]);


         if(is_array($dto->attachments) && count($dto->attachments) > 0) {
             $case->removeAttachments();
             $case->addAttachments($dto->attachments);
         }


         return redirect()->back()->with('messages', AlertDto::success(__('Updated')));
     }
}
