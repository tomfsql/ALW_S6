<?php

/**
 * Ce script sert juste à présenter l'usage de SimpleComicRepository.
 * Il n'a pas vocation à rester dans le framework à terme.
 */

// ces 2 lignes sont inutile pour une utilisation depuis du code dans le framework
require_once 'App/Repositories/SimpleComicRepository.php';
require_once 'App/Utilities/FileStorage.php';

// ligne à ajouter en haut du script où on veut utiliser SimpleComicRepository
use App\Repositories\SimpleComicRepository;

// d'abord créer un objet repository avant de pouvoir appeler ses méthodes
$repo = new SimpleComicRepository();

// example 1 : récupérer la liste des BD
// $all_comics = $repo->listComics();
// var_dump($all_comics);

// example 2 : récupérer la liste des planches d'une BD donnée
// $comic_strips = $repo->listStrips('code-pirates');
// var_dump($comic_strips);

// example 3 : récupérer toute une planche de BD donnée
$complete_comic_strip = $repo->getSimpleComic('code-pirates', 1);
var_dump($complete_comic_strip);

// example 4 : une BD simplifiée
// $simpleRepo->createSimpleComic("code-pirates", 2, [
//     "comic" => ["title" => "Les pirates du code", "author" => "moi", "created" => "2025-02-01"],
//     "strip" => ["title" => "#001 Trois blagues", "created" => "2025-02-02"],
//     "panels" => []
// ]);

// // Supprimer une BD
// $simpleRepo->deleteSimpleComic("code-pirates");