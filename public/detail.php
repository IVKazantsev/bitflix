<?php

require_once __DIR__ . '/../boot.php';

$genres = getGenres();
$movies = getMovies();

$siteTitle = option('APP_NAME', 'Bitflix');

$movieId = null;
if (isset($_GET['movieId']) && is_numeric($_GET['movieId']))
{
	$movieId = (int)$_GET['movieId'];
}

$movie = getMovieById($movieId);

echo view('layout', [
	'title' => $movie['title'] ?? $siteTitle,
	'genres' => $genres,
	'content' => ($movie) ? view('pages/detail', [
		'movie' => $movie,
		'ratingBooleanArray' => castingRatingToArray($movie['rating']),
	]) : view('components/empty-detail'),
]);