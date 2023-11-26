<?php

function filterMovieCards(array $movies, ?string $search): array
{
	$suitableMovies = [];
	foreach ($movies as $movie)
	{
		if (
			($search !== null)
			&& (mb_stripos($movie['title'], $search, 0, 'UTF-8') === false)
			&& (mb_stripos($movie['original-title'], $search, 0, 'UTF-8') === false)
		)
		{
			continue;
		}

		$suitableMovies[] = $movie;
	}

	return $suitableMovies;
}

function formatRating(float $rating): string
{
	return number_format($rating, option('DECIMALS_IN_RATING', 1), '.', '');
}

function castingRatingToArray(int $rating): array
{
	$tenPointRatingUnits = [];
	for ($i = 0; $i < 10; $i++)
	{
		if ($i < floor($rating))
		{
			$tenPointRatingUnits[] = true;
		}
		else
		{
			$tenPointRatingUnits[] = false;
		}
	}

	return $tenPointRatingUnits;
}

function computeMenuItems(array $genres, ?array $optionalParams = null): array
{
	if ($optionalParams === null)
	{
		$optionalParams = [];
	}

	$optionalParamsWithLinks = changeStartOfArrayKeys($optionalParams, '/');
	$genresWithLinks = changeStartOfArrayKeys($genres, '/?genre=');

	return array_merge($optionalParamsWithLinks, $genresWithLinks);
}

function changeStartOfArrayKeys(array $arr, string $newStartOfKey): array
{
	$changedArray = [];
	foreach ($arr as $key => $arrItem)
	{
		$changedArray[$newStartOfKey . $key] = $arrItem;
	}

	return $changedArray;
}

function getMovieById(array $movies, ?int $movieId): ?array
{

	if ($movieId === null)
	{
		return null;
	}
	foreach ($movies as $movie)
	{
		if ($movie['id'] !== $movieId)
		{
			continue;
		}

		return $movie;
	}

	return null;
}