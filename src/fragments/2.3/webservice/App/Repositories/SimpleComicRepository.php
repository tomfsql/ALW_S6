<?php

namespace App\Repositories;

use App\Utilities\FileStorage;

class SimpleComicRepository {
    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage('Data/SimpleComics');
    }

    /**
     * Retourne les données (décodées) d'un SimpleComic
     *
     * @param string $comicId  nom court de la BD
     * @param integer $stripId id de la planche
     * @return array|null
     */
    public function getSimpleComic(string $comicId, int $stripId): ?array {
        return $this->storage->readJson("$comicId/strip-$stripId.json");
    }

    public function createSimpleComic(string $comicId, int $stripId, array $data): bool {
        return $this->storage->writeJson("$comicId/strip-$stripId.json", $data);
    }

    public function updateSimpleComic(string $comicId, int $stripId, array $data): bool {
        return $this->createSimpleComic($comicId, $stripId, $data);
    }

    public function deleteSimpleComic(string $comicId): bool {
        return $this->storage->deleteDirectory($comicId);
    }

    public function listComics(): array {
        $comics = [];

        foreach ($this->storage->listDirectories('') as $comicId) {
            $stripFiles = $this->storage->listFiles($comicId, 'json');

            if (empty($stripFiles)) {
                continue; // Aucun strip, on ignore cette BD
            }

            // Trie les fichiers pour prendre le premier strip (strip-1.json, strip-2.json...)
            usort($stripFiles, function ($a, $b) {
                return $this->extractStripNumber($a) <=> $this->extractStripNumber($b);
            });

            // Lire le premier strip trouvé pour extraire la partie "comic"
            $firstStrip = $this->storage->readJson("$comicId/$stripFiles[0]");
            if ($firstStrip && isset($firstStrip['comic'])) {
                $comics[$comicId] = $firstStrip['comic'];
            }
        }

        return $comics;
    }

    public function listStrips(string $comicId): array {
        $strips = [];

        foreach ($this->storage->listFiles($comicId, 'json') as $file) {
            $stripNumber = $this->extractStripNumber($file);
            $data = $this->storage->readJson("$comicId/$file");

            if ($stripNumber !== null && $data && isset($data['strip'])) {
                $strips[$stripNumber] = $data['strip'];
            }
        }

        ksort($strips); // Trie par numéro de strip
        return $strips;
    }

    private function extractStripNumber(string $filename): ?int {
        if (preg_match('/strip-(\d+)\.json$/', $filename, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
