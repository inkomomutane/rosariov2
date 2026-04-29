<?php

namespace App\Http\Controllers\MyCases;

use App\Data\AlertDto;
use App\Data\PatientCaseRequestDto;
use App\Enum\PatientCaseStatus;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class StoreMyCase
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function __invoke(PatientCaseRequestDto $dto)
     {
         $person = auth()->user()->person()->firstOrCreate([
             'user_id' => auth()->id()
         ], [
             'name' => auth()->user()->name,
             'last_name' => ''
         ]);

        $case = $person->cases()->create([
             'case_code' => 'CASE-' . \Str::ulid(),
             'person_id' => $person->id,
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

         $case->removeAttachments();
         $case->addAttachments($dto->attachments);

         return redirect()->route('my-cases')->with('messages', AlertDto::success(__('Created')));
     }
}
