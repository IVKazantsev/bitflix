<?php

require_once __DIR__ . '/../boot.php';
/**
 * @var array $genres
 * @var array $movies
 */

$title = option('APP_NAME', 'Bitflix');

$genre = $_GET['genre'] ?? null;
$search = $_GET['search'] ?? null;
$suitableMovies = processMovieCards($movies, $genres, $genre, $search);

echo view('layout', [
	'title' => $title,
	'genres' => $genres,
	'content' => view('pages/index', [
		'movies' => $suitableMovies,
	]),
]);