<?php

require_once __DIR__ . '/../boot.php';

$connection = getDbConnection();

$genres = getAllGenres($connection);

echo view('layout', [
	'title' => 'Избранное',
	'genres' => $genres,
	'content' => view('pages/favorite'),
]);