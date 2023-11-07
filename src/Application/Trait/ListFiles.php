<?php

namespace App\Application\Trait;

trait ListFiles
{

    private function listFiles(string $path): array
    {
        $path = str_ends_with("/", $path) ? $path : $path . "/";
        $result = [];

        if (is_dir($path)) {
            $files = scandir($path);

            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $path . $file;
                    if (is_file($filePath)) {
                        $result[] = $filePath;
                    }
                }
            }
        }

        return $result;
    }
}