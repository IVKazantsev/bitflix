<?php
/**
 * @var array $movie
 * @var array $ratingBooleanArray
 */

?>
<main class="main single-film-main">
	<div class="film-card">
		<div class="film-header">
			<div class="film-header-first-line">
				<div class="film-name">
					<?= $movie['title'] ?> (<?= $movie['release-date'] ?>)
				</div>
				<div class="add-film-to-favorites"></div>
			</div>
			<div class="film-header-second-line">
				<div class="english-film-name film-item-english-name-text">
					<?= $movie['original-title'] ?>
				</div>
				<div class="age">
					<?= $movie['age-restriction'] ?>+
				</div>
			</div>
		</div>
		<div class="film-body">
			<div class="film-img-container">
				<img
					class="film-img"
					src="./img/<?= $movie['id'] ?>.jpg"
					alt="Обложка фильма &#171;<?= $movie['title'] ?>&#187;">
			</div>
			<div class="full-film-description">
				<?= view('components/rating', [
					'rating' => $movie['rating'],
					'ratingBooleanArray' => $ratingBooleanArray,
				]) ?>
				<div class="film-header-for-content">О фильме</div>
				<div class="about-film">
					<table class="table-about-film">
						<tbody>
						<tr>
							<td class="characteristics">Год производства:</td>
							<td class="specific-characteristics"><?= $movie['release-date'] ?></td>
						</tr>
						<tr>
							<td class="characteristics">Режиссер:</td>
							<td class="specific-characteristics"><?= $movie['director'] ?></td>
						</tr>
						<tr>
							<td class="characteristics">В главных ролях:</td>
							<td class="specific-characteristics"><?= implode(', ', $movie['cast']) ?></td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="film-header-for-content film-description-title">Описание</div>
				<div class="film-description"><?= $movie['description'] ?></div>
			</div>
		</div>
	</div>
</main>