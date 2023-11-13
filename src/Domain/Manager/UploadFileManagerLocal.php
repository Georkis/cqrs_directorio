<?php

declare(strict_types=1);

namespace App\Domain\Manager;

interface UploadFileManagerLocal
{
    public function uploadFileBase64(string $fileBase64): string;

}