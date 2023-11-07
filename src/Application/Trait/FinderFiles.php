<?php

namespace App\Application\Trait;

use Symfony\Component\Finder\Finder;

trait FinderFiles
{
    private function finder (string $dirFiles, array $extensions = []): array
    {
        $result = [];

        $searchExtension = [];

        foreach ($extensions as $extension){
            $searchExtension[] = "*.".$extension;
        }

        $finder = new Finder();
        $finder->files()->in($dirFiles);
        $finder->name($searchExtension);

        foreach ($finder as $file){
            $result[] = $file->getRealPath();
        }

        return $result;

    }
}