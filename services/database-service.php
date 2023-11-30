<?php

function getDbConnection()
{
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
			throw new RuntimeException($error);
		}

		$encodingResult = mysqli_set_charset($connection, 'utf-8');
		if ($encodingResult)
		{
			throw new RuntimeException(mysqli_error($connection));
		}
	}

	return $connection;
}

function getAllGenres(): array
{
	$connection = getDbConnection();
	$query = "SELECT g.CODE, g.NAME
			  FROM genre g
              LIMIT 100";

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$genres = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$genres[$row['CODE']] = $row['NAME'];
	}

	return $genres;
}

function getResultRowByQuery(string $query): ?array
{
	$connection = getDbConnection();

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	return (mysqli_fetch_assoc($result)) ?: null;
}

function getGenresByMovieId(int $movieId): ?array
{
	$query = "SELECT GROUP_CONCAT(DISTINCT g.NAME) AS GENRES_ARRAY
		           FROM movie_genre mg
		           INNER JOIN genre g on mg.GENRE_ID = g.ID
		           WHERE mg.MOVIE_ID = '$movieId'";

	$row = getResultRowByQuery($query);

	if ($row === null || $row['GENRES_ARRAY'] === null)
	{
		return null;
	}

	return explode(',', $row['GENRES_ARRAY']);
}

function getActorsByMovieId(int $movieId): ?array
{
	$query = "SELECT GROUP_CONCAT(DISTINCT a.NAME) AS ACTORS_ARRAY
				FROM movie_actor ma 
				INNER JOIN actor a on ma.ACTOR_ID = a.ID
				WHERE  ma.MOVIE_ID = '$movieId'";

	$row = getResultRowByQuery($query);

	if ($row === null || $row['ACTORS_ARRAY'] === null)
	{
		return null;
	}

	return explode(',', $row['ACTORS_ARRAY']);
}

function getMovieByResultRow(array $row): array
{
	return [
		'id' => $row['ID'],
		'title' => $row['TITLE'],
		'original-title' => $row['ORIGINAL_TITLE'],
		'description' => $row['DESCRIPTION'],
		'duration' => $row['DURATION'],
		'genres' => getGenresByMovieId($row['ID']),
		'cast' => getActorsByMovieId($row['ID']),
		'director' => $row['DIRECTOR_NAME'],
		'age-restriction' => $row['AGE_RESTRICTION'],
		'release-date' => $row['RELEASE_DATE'],
		'rating' => $row['RATING'],
	];
}

function getMoviesQuery(): string
{
	return "SELECT m.ID, m.TITLE, m.RATING,
		       m.AGE_RESTRICTION, m.DESCRIPTION, m.DURATION,
		       m.ORIGINAL_TITLE, m.RELEASE_DATE,
		       d.NAME AS DIRECTOR_NAME
		FROM movie m
			INNER JOIN director d on m.DIRECTOR_ID = d.ID";
}

function getMovies(?string $genre = null): array
{
	$connection = getDbConnection();

	if ($genre !== null)
	{
		$genre = mysqli_real_escape_string($connection, $genre);
	}

	$allFilmsQuery = getMoviesQuery();

	$filteringPartOfQuery = "
	INNER JOIN movie_genre mg on m.ID = mg.MOVIE_ID
	INNER JOIN genre g on mg.GENRE_ID = g.ID
	WHERE g.CODE = '$genre'
	";

	$groupingPartOfQuery = "
	GROUP BY m.ID
	LIMIT 100
	";

	$query = ($genre === null) ? $allFilmsQuery . $groupingPartOfQuery
		: $allFilmsQuery . $filteringPartOfQuery . $groupingPartOfQuery;

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movies[] = getMovieByResultRow($row);
	}

	return $movies;
}

function getMovieById(?int $movieId): ?array
{
	if ($movieId === null)
	{
		return null;
	}

	$query = getMoviesQuery() . "
	WHERE m.ID = '$movieId'
	";

	$row = getResultRowByQuery($query);
	if ($row === null || $row['ID'] === null)
	{
		return null;
	}

	return getMovieByResultRow($row);
}