<?php

require_once __DIR__ . '/../boot.php';

$connection = getDbConnection();

$genres = getAllGenres($connection);

$title = option('APP_NAME', 'Bitflix');

echo view('layout', [
	'title' => 'Добавить фильм',
	'genres' => $genres,
	'content' => view('pages/add-film'),
]);