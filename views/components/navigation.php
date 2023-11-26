<?php
/**
 * @var array $menuItems
 */

?>

<header class="nav-section">
	<div class="container nav-container">
		<a class="bitflix-logo" href="/">
			<img class="logo-img" src="/img/logo.svg" alt="Логотип">
		</a>
		<nav class="nav">
			<ul class="nav-list">
				<?php
				foreach ($menuItems as $key => $menuItem): ?>
					<li class="nav-item"><a class="link-of-nav-item" href="<?= $key ?>"><?= $menuItem ?></a></li>
				<?php
				endforeach; ?>
			</ul>
		</nav>
	</div>
</header>