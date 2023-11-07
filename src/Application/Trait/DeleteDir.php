<?php

namespace App\Application\Trait;

trait DeleteDir
{

    private function deleteDir($dir): void
    {
        $it = new \RecursiveDirectoryIterator(directory: $dir, flags: \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator(iterator: $it, mode: \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }
}