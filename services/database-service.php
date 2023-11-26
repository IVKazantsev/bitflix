<?php

function getDbConnection()
{
	/**
	 * @throws Exception
	 */

	static $connection = null;

	if ($connection === null)
	{
		$dbHost = option('DB_HOST');
		$dbUser = option('DB_USER');
		$dbPassword = option('DB_PASSWORD');
		$dbName = option('DB_NAME');

		$connection = mysqli_init();

		$connected = mysqli_real_connect($connection, $dbHost,
			$dbUser, $dbPassword, $dbName);
		if (!$connected)
		{
			$error = mysqli_connect_errno() . ': ' . mysqli_connect_error();
			throw new \RuntimeException($error);
		}

		$encodingResult = mysqli_set_charset($connection, 'utf-8');
		if ($encodingResult)
		{
			throw new \RuntimeException(mysqli_error($connection));
		}
	}

	return $connection;
}

function getGenres(): array
{
	/**
	 * @throws Exception
	 */

	$connection = getDbConnection();
	$query = "
		SELECT CODE, NAME FROM genre
		LIMIT 100
		";

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new \RuntimeException(mysqli_error($connection));
	}

	$genres = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$genres[$row['CODE']] = $row['NAME'];
	}

	return $genres;
}

function getMovies(?string $genre = null): array
{
	$connection = getDbConnection();

	if ($genre !== null)
	{
		$genre = mysqli_real_escape_string($connection, $genre);
	}

	$allFilmsQuery = "SELECT m.ID, m.TITLE, m.DIRECTOR_ID, m.RATING,
		       m.AGE_RESTRICTION, m.DESCRIPTION, m.DURATION,
		       m.ORIGINAL_TITLE, m.RELEASE_DATE,
		       d.NAME AS DIRECTOR_NAME,
		       GROUP_CONCAT(DISTINCT g.NAME) AS GENRES_ARRAY,
		       GROUP_CONCAT(DISTINCT a.NAME) AS ACTORS_ARRAY
		FROM movie m
		    INNER JOIN movie_genre mg on m.ID = mg.MOVIE_ID
			INNER JOIN genre g on mg.GENRE_ID = g.ID
			INNER JOIN director d on m.DIRECTOR_ID = d.ID
		    INNER JOIN movie_actor ma on m.ID = ma.MOVIE_ID
			INNER JOIN actor a on ma.ACTOR_ID = a.ID
			";

	$sortingPartOfQuery = "
		WHERE g.CODE = '$genre'
	";

	$groupingPartOfQuery = "
		GROUP BY m.ID
		LIMIT 100
		";

	$query = ($genre === null) ? $allFilmsQuery . $groupingPartOfQuery
		: $allFilmsQuery . $sortingPartOfQuery . $groupingPartOfQuery;

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new \RuntimeException(mysqli_error($connection));
	}

	$movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movies[] = [
			'id' => (int)$row['ID'],
			'title' => $row['TITLE'],
			'original-title' => $row['ORIGINAL_TITLE'],
			'description' => $row['DESCRIPTION'],
			'duration' => $row['DURATION'],
			'genres' => explode(',', $row['GENRES_ARRAY']),
			'cast' => explode(',', $row['ACTORS_ARRAY']),
			'director' => $row['DIRECTOR_NAME'],
			'age-restriction' => $row['AGE_RESTRICTION'],
			'release-date' => $row['RELEASE_DATE'],
			'rating' => (float)$row['RATING'],
		];
	}

	return $movies;
}