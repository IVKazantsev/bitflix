<?php
/**
 * @var array $movies
 */

?>

<main class="main">
	<?php
	if (empty($movies)): ?>
		<div class="no-films-container">
			<div class="no-films-header">Ничего не найдено</div>
			<div class="no-films-description">Попробуйте ввести название фильма</div>
		</div>
	<?php
	endif; ?>

	<ul class="films-list">
		<?php
		foreach ($movies as $movie): ?>
			<?= view('components/movie-card', ['movie' => $movie]) ?>
		<?php
		endforeach; ?>
	</ul>
</main>