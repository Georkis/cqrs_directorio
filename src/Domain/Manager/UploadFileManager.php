<?php declare(strict_types=1);

namespace App\Domain\Manager;

interface UploadFileManager
{
    public function uploadFile(string $filePath, string $uploadPath = "", string $fileName = "", bool $publicRead = false): string;

    public function uploadFileUrl(string $fileUrl, string $uploadPath = "", string $fileName = "", bool $publicRead = false): string;

    public function uploadFileBase64(string $fileBase64, string $uploadPath, string $fileName, bool $publicRead = false): string;

    public function getPublicUrl(string $privateUrl, int $expirationInSeconds): string;

    public function getFileContent(string $fileUrl);
}