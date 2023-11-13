<?php

namespace App\Infrastructure\Manager;

use _PHPStan_1623582d5\Nette\Utils\ImageException;
use App\Domain\Manager\UploadFileManagerLocal;

//todo dev
class UploadFileLocal implements UploadFileManagerLocal
{
    private string $folderAvatar;


    public function __construct(
        string $folderAvatar,
    )
    {
        $this->folderAvatar = $folderAvatar;
    }
    public function uploadFileBase64(
        string $filebase64,
    ): string
    {
        if(!is_dir($this->folderAvatar)){
            if (!mkdir(directory: $concurrentDirectory = $this->folderAvatar, recursive: true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        $explodeStringBase64 = explode(separator:",", string: $filebase64)[1];

        $imageData = base64_decode($explodeStringBase64);
        if(!$imageInfo = getimagesizefromstring($imageData)){
            throw new ImageException();
        }
        $extension = explode(separator:"/", string:$imageInfo['mime'])[1];
        $fileName = uniqid(prefix: "", more_entropy: true).".".$extension;

        $handle = fopen(filename: $this->folderAvatar.$fileName, mode: "w");
        fwrite($handle, $imageData);
        fclose($handle);

        return $fileName;
    }
}