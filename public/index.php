<?php

require_once __DIR__ . '/../boot.php';

$genres = getGenres();

$title = option('APP_NAME', 'Bitflix');

$genre = $_GET['genre'] ?? null;
$search = $_GET['search'] ?? null;

$movies = getMovies($genre);

$suitableMovies = filterMovieCards($movies, $search);

echo view('layout', [
	'title' => $title,
	'genres' => $genres,
	'content' => view('pages/index', [
		'movies' => $suitableMovies,
	]),
]);