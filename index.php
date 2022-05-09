<?php
	require_once("templates/header.php");
	require_once("dao/MovieDAO.php");
	
	// DAO dos filmes
	$movieDao = new MovieDAO($conn, $BASE_URL);
	$latestMovies = $movieDao -> getLatestMovies();
	$actionMovies = $movieDao -> getMoviesByCategory("Ação");
	$comedyMovies = $movieDao -> getMoviesByCategory("Comédia");
?>

<div class="container-fluid">
	<div id="mainSlider" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#mainSlider" data-slide-to="0" class="active"></li>
			<li data-target="#mainSlider" data-slide-to="1"></li>
			<li data-target="#mainSlider" data-slide-to="2"></li>		 
		</ol>	 
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img src="img/TheBatman.jpg" class="d-block w-100" alt="The Batman">
			</div>
			<div class="carousel-item">
				<img src="img/Sonic.jpg" class="d-block w-100" alt="Sonic">
			</div>
			<div class="carousel-item">
				<img src="img/DrEstranho2.jpg" class="d-block w-100" alt="Doutor Estranho 2">
			</div>
		</div>
	</div>
</div>
<div id="main-container" class="container-fluid">
	<h2 class="section-title"> Filmes novos </h2>
	<p class="section-description">
		Veja as críticas dos últimos filmes adicionados no moviews
	</p>
	<div class="movies-container">
		<?php foreach($latestMovies as $movie): ?>
			<?php require("templates/movie_card.php"); ?>
		<?php endforeach; ?>
		<?php if (count($latestMovies) === 0): ?>
			<p class="empty-list"></p>
		<?php endif; ?>
	</div>
	<h2 class="section-title"> Ação </h2>
	<p class="section-description"> Veja os melhores filmes de ação </p>
	<div class="movies-container">
		<?php foreach($actionMovies as $movie): ?>
			<?php require("templates/movie_card.php"); ?>
		<?php endforeach; ?>
		<?php if (count($actionMovies) === 0): ?>
			<p class="empty-list"></p>
		<?php endif; ?>
	</div>
	<h2 class="section-title"> Comédia </h2>
	<p class="section-description"> Veja os melhores filmes de comédia </p>
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