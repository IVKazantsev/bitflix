<?php
/**
 * @var array $movie
 */

?>
<li class="film-item">
	<div class="film-item-img-container">
		<img
			class="film-item-img"
			src="./img/<?= $movie['id'] ?>.jpg"
			alt="Обложка фильма	&#171;<?= $movie['title'] ?>&#187;">
	</div>
	<div class="film-item-text">
		<div class="film-item-name">
			<div class="film-item-name-text"><?= $movie['title'] ?> (<?= $movie['release-date'] ?>)</div>
			<div class="film-item-english-name-text"><?= $movie['original-title'] ?></div>
		</div>
		<div class="film-item-content">
			<p class="film-item-content-text"><?= truncate(
					$movie['description'],
					option('TRUNCATE_FILM_DESCRIPTION', 100)
				) ?></p>
		</div>
		<div class="film-item-description">
			<div class="film-item-duration">
				<?= $movie['duration'] ?> мин. / <?= convertMinsToHoursMins($movie['duration']) ?>
			</div>
			<div class="film-item-genre"><?= implode(', ', $movie['genres']) ?></div>
		</div>
	</div>
	<div class="hover-container-for-films">
		<a href="/detail.php?movieId=<?= $movie['id'] ?>" class="link more-link">Подробнее</a>
	</div>
</li>