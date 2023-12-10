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

		$encodingResult = mysqli_set_charset($connection, 'utf8');
		if (!$encodingResult)
		{
			throw new RuntimeException(mysqli_error($connection));
		}
	}

	return $connection;
}

function getAllGenres($connection): array
{
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

function getGenresByMovieId($connection, int $movieId): ?array
{
	$query = "SELECT DISTINCT g.NAME AS GENRE_NAME
		           FROM movie_genre mg
		           INNER JOIN genre g on mg.GENRE_ID = g.ID
		           WHERE mg.MOVIE_ID = '$movieId'";

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$genres = [];

	while ($row = mysqli_fetch_assoc($result))
	{
		$genres[] = $row['GENRE_NAME'];
	}

	return $genres;
}

function getActorsByMovieId($connection, int $movieId): ?array
{
	$query = "SELECT DISTINCT a.NAME AS ACTOR_NAME
				FROM movie_actor ma 
				INNER JOIN actor a on ma.ACTOR_ID = a.ID
				WHERE  ma.MOVIE_ID = '$movieId'";

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$actors = [];

	while ($row = mysqli_fetch_assoc($result))
	{
		$actors[] = $row['ACTOR_NAME'];
	}

	return $actors;
}

function getMovieByResultRow($connection, array $row): array
{
	return [
		'id' => $row['ID'],
		'title' => $row['TITLE'],
		'original-title' => $row['ORIGINAL_TITLE'],
		'description' => $row['DESCRIPTION'],
		'duration' => $row['DURATION'],
		'genres' => getGenresByMovieId($connection, $row['ID']),
		'cast' => getActorsByMovieId($connection, $row['ID']),
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

function getMovies($connection, ?string $genre = null, ?string $search = null): array
{
	$query = getMoviesQuery();

	if ($genre !== null)
	{
		$genre = mysqli_real_escape_string($connection, $genre);

		$filterByGenre = "
			INNER JOIN movie_genre mg on m.ID = mg.MOVIE_ID
			INNER JOIN genre g on mg.GENRE_ID = g.ID
			WHERE g.CODE = '$genre'
		";

		$query .= $filterByGenre;
	}

	if ($search !== null)
	{
		$search = mysqli_real_escape_string($connection, $search);

		$filterBySearch = ($genre === null)
			? "
			WHERE m.TITLE LIKE '%$search%' OR m.ORIGINAL_TITLE LIKE '%$search%'
		"
			: "
			AND (m.TITLE LIKE '%$search%' OR m.ORIGINAL_TITLE LIKE '%$search%')
		";

		$query .= $filterBySearch;
	}

	$sortPartOfQuery = "
		ORDER BY m.ID
		LIMIT 100
	";

	$query .= $sortPartOfQuery;

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movies[] = getMovieByResultRow($connection, $row);
	}

	return $movies;
}

function getMovieById($connection, ?int $movieId): ?array
{
	if ($movieId === null)
	{
		return null;
	}

	$query = getMoviesQuery() . "
	WHERE m.ID = '$movieId'
	";

	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		throw new RuntimeException(mysqli_error($connection));
	}

	$row = (mysqli_fetch_assoc($result)) ?: null;
	if ($row === null || $row['ID'] === null)
	{
		return null;
	}

	return getMovieByResultRow($connection, $row);
}