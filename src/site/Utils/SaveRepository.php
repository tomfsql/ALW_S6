<?php

// namespace App\Repositories;

// use App\Utilities\FileStorage;

class SaveRepository
{
    private FileStorage $storage;
    private string $initialSavePath;
    private array $loadedSaves;

    public function __construct(string $saveDir, string $initialSavePath)
    {
        // En mode procédural, on pourrait avoir besoin de require_once
        // Mais si on est dans le framework, l'autoloader gérera FileStorage.
        $this->storage = new FileStorage($saveDir);
        $this->initialSavePath = $initialSavePath;
        $this->loadedSaves = [];
    }

    public function exists(string $username): bool
    {
        return file_exists($this->storage->getBasePath().$username.".json");
    }

    public function initSave(string $username): void
    {
        $initialSavePath = __DIR__ . '/../Data/Saves/test.json';
        $targetPath = __DIR__ . '/../Data/Saves/' . $username . '.json';
        $dossierDestination = dirname($targetPath);
        if (!is_dir($dossierDestination)) {
            if (!mkdir($dossierDestination, 0777, true)) {
                die("ERREUR FATALE : Impossible de créer le dossier : " . $dossierDestination);
            }
        }
        if (!is_writable($dossierDestination)) {
            chmod($dossierDestination, 0777);
            if (!is_writable($dossierDestination)) {
                die("ERREUR FATALE : PHP n'a pas le droit d'écrire dans : " . $dossierDestination);
            }
        }
        if (!$this->exists($username)) {
            copy($this->initialSavePath, $this->storage->getBasePath().$username.".json");
        }
    }

    public function load(string $username): array
    {
        if (!$this->exists($username)) {
            $this->initSave($username);
        }
        if (!isset($this->loadedSaves[$username])) {
            $this->loadedSaves[$username] = $this->storage->readJson($username . ".json") ?? [];
        }
        return $this->loadedSaves[$username];
    }

    public function save(string $username, object $data): void
    {
        $this->storage->writeJson($username . ".json", $data);
    }

    public function getInventory(string $username): object
    {
        $save = $this->load($username);
        return $save->inventory ?? (object)[];
    }

    public function getProduct(string $username, string $product): int
    {
        $inventory = $this->getInventory($username);
        return $inventory[$product] ?? 0;
    }

    public function setProduct(string $username, string $product, int $value): void
    {
        $save = $this->load($username);
        $save->inventory->{$product} = $value;
        $this->save($username, $save);
    }

    public function getBuildings(string $username): object
    {
        $save = $this->load($username);
        return $save->buildings ?? [];
    }

    public function getLevel(string $username, string $generator): int
    {
        $save = $this->load($username);
        foreach ($save->buildings as $b) {
            if ($b->id === $generator) {
                return $b->level;
            }
        }
        return 0;
    }

    public function setLevel(string $username, string $generator, int $value): void
    {
        $save = $this->load($username);
        $found = false;
        foreach ($save['buildings'] as &$b) {
            if ($b['id'] === $generator) {
                $b['level'] = $value;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $save['buildings'][] = [
                'id' => $generator,
                'level' => $value,
                'last_harvest' => null
            ];
        }
        $this->save($username, $save);
    }
}
