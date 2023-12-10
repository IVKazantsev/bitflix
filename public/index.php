<?php

require_once __DIR__ . '/../boot.php';

$connection = getDbConnection();

$genres = getAllGenres($connection);

$title = option('APP_NAME', 'Bitflix');

$genre = $_GET['genre'] ?? null;
$search = $_GET['search'] ?? null;

$movies = getMovies($connection, $genre, $search);

echo view('layout', [
	'title' => $title,
	'genres' => $genres,
	'content' => view('pages/index', [
		'movies' => $movies,
	]),
]);