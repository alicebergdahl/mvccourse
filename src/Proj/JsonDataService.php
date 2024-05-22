<?php

namespace App\Proj;

/**
 * Klassen JsonDataService hanterar inläsning av JSON-data från filer.
 */
class JsonDataService
{
    /**
     * Läser in och returnerar JSON-data från en fil.
     *
     * @param string $filePath Sökvägen till JSON-filen.
     * @return array<mixed>
     */
    public function loadItems(string $filePath): array
    {
        $jsonString = file_get_contents($filePath);

        if ($jsonString === false) {
            throw new \RuntimeException("Failed to read JSON file: $filePath");
        }

        return json_decode($jsonString, true);
    }

    /**
     * Läser in och returnerar JSON-data från en fil.
     *
     * @param string $filePath Sökvägen till JSON-filen.
     * @return array<mixed>
     */
    public function loadButtons(string $filePath): array
    {
        $jsonString = file_get_contents($filePath);

        if ($jsonString === false) {
            throw new \RuntimeException("Failed to read JSON file: $filePath");
        }

        return json_decode($jsonString, true);
    }
}