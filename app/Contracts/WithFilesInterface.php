<?php

namespace App\Contracts;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface WithFilesInterface
{
    public function getUploadedAttachmentsAttribute(): null|array|Collection;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachments(array $attachments): void;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachment(UploadedFile|\File $attachment): void;

    public function removeAttachments(): void;
}
