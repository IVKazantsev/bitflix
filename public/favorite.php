<?php

require_once __DIR__ . '/../boot.php';
/**
 * @var array $genres
 */

echo view('layout', [
	'title' => 'Избранное',
	'genres' => $genres,
	'content' => view('pages/favorite'),
]);