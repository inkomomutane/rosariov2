<?php

namespace App\Traits;


use App\Data\FileData;
use File;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait WithFiles
{
    public function getUploadedAttachmentsAttribute(): null|array|Collection
    {
        return $this->getMedia('attachments')->map(fn($file) => $file ? FileData::fromModel($file) : null);
    }


    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @var    UploadedFile[]|File[]|array<UploadedFile|File> $attachments
     */
    public function addAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->addMedia($attachment)->toMediaCollection('attachments');
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachment(UploadedFile|File $attachment): void
    {
        $this->addMedia($attachment)->toMediaCollection('attachments');
    }

    public function removeAttachments(): void
    {
        $this->clearMediaCollection('attachments');
    }

}
