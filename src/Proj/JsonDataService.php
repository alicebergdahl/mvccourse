<?php

namespace App\Proj;

class JsonDataService
{
    public function loadItems(string $filePath): array
    {
        $jsonString = file_get_contents($filePath);
        return json_decode($jsonString, true);
    }

    public function loadButtons(string $filePath): array
    {
        $jsonString = file_get_contents($filePath);
        return json_decode($jsonString, true);
    }
}