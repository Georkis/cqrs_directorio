<?php

namespace App\Application\Trait;

trait UnZip
{

    private function unZip(string $zipPath, string $unzipPath): void
    {
        $zip = new \ZipArchive();

        $zip->open($zipPath);

        if (!is_dir($unzipPath)) {
            if (!mkdir($unzipPath, permissions: 0755, recursive: true) && !is_dir($unzipPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $unzipPath));
            }
        }

        $zip->extractTo($unzipPath);

        $zip->close();
    }
}