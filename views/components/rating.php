<?php
/**
 * @var float $rating
 * @var array $ratingBooleanArray
 */

?>

<div class="rating">
	<div class="rating-imgs">
		<?php
		foreach ($ratingBooleanArray as $ratingUnit): ?>
			<?php
			if ($ratingUnit): ?>
				<img class="enabled-rating" src="/img/enabled-rating.svg" alt="">
				<?php
				continue ?>
			<?php
			endif; ?>
			<img class="disabled-rating" src="/img/disabled-rating.svg" alt="">
		<?php
		endforeach; ?>
	</div>
	<div class="rating-text">
		<?= formatRating($rating) ?>
	</div>
</div>