<?php

function view(string $path, array $variables = []): string
{
	/** @throws Exception */

	if (!preg_match('/^[0-9A-Za-z\/_-]+$/', $path))
	{
		throw new \RuntimeException('Invalid template path');
	}

	$absolutePath = ROOT . "/views/$path.php";

	if (!file_exists($absolutePath))
	{
		throw new \RuntimeException('Template not found');
	}

	extract($variables);

	ob_start();

	require $absolutePath;

	return ob_get_clean();
}

function truncate(string $text, ?int $maxLength = null): string
{
	if ($maxLength === null)
	{
		return $text;
	}

	$cropped = mb_substr($text, 0, $maxLength, 'UTF-8');

	if ($cropped !== $text)
	{
		return "$cropped...";
	}

	return $text;
}

function convertMinsToHoursMins(int $mins): string
{
	return sprintf("%02d:%02d", floor($mins / 60), $mins % 60);
}