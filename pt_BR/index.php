<?php
	require_once("templates/header.php");
	require_once("dao/MovieDAO.php");
	
	// DAO dos filmes
	$movieDao = new MovieDAO($conn, $BASE_URL);
	$latestMovies_01 = $movieDao -> getLatestMovies(1);
	$latestMovies_02 = $movieDao -> getLatestMovies(2);
	$actionMovies = $movieDao -> getMoviesByCategory("Ação");
	$comedyMovies = $movieDao -> getMoviesByCategory("Comédia");
?>

<div id="main-container" class="container-fluid">
	<div class="container-language">
		<a href="<?= $BASE_URL ?>../pt_BR">
			<img class="selected" src="<?= $BASE_URL ?>../assets/br_flag.png"></img>
		</a>
		<a href="<?= $BASE_URL ?>../en_US">
			<img src="<?= $BASE_URL ?>../assets/us_flag.png"></img>
		</a>
	</div>
	<h2 class="section-title"> Filmes novos </h2>
	<p class="section-description">
		Veja as críticas dos últimos filmes adicionados, em um formato inovador!
	</p>
	<div class="container-carousel">
		<div id="carousel" class="carousel slide a">
			<div class="carousel-inner">
				<div class="item active">
					<div class="row">
						<?php foreach($latestMovies_01 as $movie): ?>
							<?php require("templates/movie_card.php"); ?>
						<?php endforeach; ?>
						<?php for ($t = count($latestMovies_01); $t <= 4; $t++): ?>
							<div class="card movie-card" id="empty"></div>
						<?php endfor ?>
					</div>
				</div>
				<div class="item">
					<div class="row">
						<?php foreach($latestMovies_02 as $movie): ?>
							<?php require("templates/movie_card.php"); ?>
						<?php endforeach; ?>
						<?php for ($t = count($latestMovies_02); $t < 5; $t++): ?>
							<div class="card movie-card" id="empty"></div>
						<?php endfor ?>
					</div>
				</div>
			</div>
		</div>
		<div id="float-left">
			<a class="fa fa-chevron-left btn fa-design" href="#carousel" data-slide="prev"> ANTERIOR </a>
		</div>
		<div id="float-right">
			<a class="fa fa-chevron-right btn fa-design" href="#carousel" data-slide="next"> PRÓXIMO </a>
		</div>
	</div><br><br><br>
	<h2 class="section-title"> Ação </h2>
	<p class="section-description"> Veja os melhores filmes de ação. </p>
	<div class="movies-container">
		<?php foreach($actionMovies as $movie): ?>
			<?php require("templates/movie_card.php"); ?>
		<?php endforeach; ?>
		<?php if (count($actionMovies) === 0): ?>
			<p class="empty-list"></p>
		<?php endif; ?>
	</div>
	<h2 class="section-title"> Comédia </h2>
	<p class="section-description"> Veja os melhores filmes de comédia. </p>
	<div class="movies-container">
		<?php foreach($comedyMovies as $movie): ?>
			<?php require("templates/movie_card.php"); ?>
		<?php endforeach; ?>
		<?php if (count($comedyMovies) === 0): ?>
			<p class="empty-list"></p>
		<?php endif; ?>
	</div>
</div>

<?php require_once("templates/footer.php"); ?>