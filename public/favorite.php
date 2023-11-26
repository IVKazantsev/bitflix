<?php

require_once __DIR__ . '/../boot.php';

$genres = getGenres();

echo view('layout', [
	'title' => 'Избранное',
	'genres' => $genres,
	'content' => view('pages/favorite'),
]);