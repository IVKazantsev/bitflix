<?php

require_once __DIR__ . '/../boot.php';

$genres = getGenres();

$title = option('APP_NAME', 'Bitflix');

echo view('layout', [
	'title' => 'Добавить фильм',
	'genres' => $genres,
	'content' => view('pages/add-film'),
]);