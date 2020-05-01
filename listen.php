<?php
	/*!
	* Start Bootstrap - Scrolling Nav (https://startbootstrap.com/template-overviews/scrolling-nav)
	* Copyright 2013-2019 Start Bootstrap
	* Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-scrolling-nav/blob/master/LICENSE)
	*/
	
	require('./inc/config.php');
	require('./inc/functions.php');
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		$id = latestEpID();
		if(!$id) {
			header('Location: index.php');
			exit;
		}
	}
	$ep = getep($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- https://github.com/KuJoe/podcastpanel -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>PodcastPanel</title>

  <!-- Bootstrap core CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="assets/css/scrolling-nav.css" rel="stylesheet">

  <!-- CSS for audio player (https://github.com/greghub/green-audio-player) -->
  <link rel="stylesheet" type="text/css" href="assets/css/green-audio-player.css">

</head>

<body id="page-top">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="index.php">PodcastPanel</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.php">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <section id="about">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
         	<div class="playerbox" style="display:<?php echo $showplayer; ?>;">
				<div class="playerboxtext">
					<h3><?php echo $ep['epname']; ?></h3>
					<?php echo $ep['epdesc']; ?>
				</div>
				<hr />
				<img class="playerboxart" src="<?php echo $target_dir; ?>/<?php echo $ep['art']; ?>" />
				<img class="playerboximg" src="assets/clock.png" title="Duration"/><?php echo $ep['duration']; ?><br />
				<img class="playerboximg" src="assets/size.png" title="File Size"/><?php echo $ep['size']; ?><br />
				<img class="playerboximg" src="assets/calendar.png" title="Publish Date"/><?php echo date("m/d/Y", strtotime($ep['publishdate'])); ?><br />
				<div class="latest-episode">
					<audio crossorigin preload="none">
						<source src="<?php echo $target_dir; ?>/<?php echo $ep['filename']; ?>" type="audio/mpeg">
					</audio>
				</div>
			</div>
			<div class="col-lg-6 mx-auto">
				<a href="<?php echo $rss; ?>" target="_blank" /><img class="socialicon" src="assets/rss.png" title="RSS Feed" /></a>
				<a href="<?php echo $apple; ?>" target="_blank" /><img class="socialicon" src="assets/apple.png" title="Apple Podcast" /></a>
				<a href="<?php echo $spotify; ?>" target="_blank" /><img class="socialicon" src="assets/spotify.png" title="Spotify" /></a>
				<a href="<?php echo $google; ?>" target="_blank" /><img class="socialicon" src="assets/google.png" title="Google Play" /></a>
				<a href="<?php echo $youtube; ?>" target="_blank" /><img class="socialicon" src="assets/youtube.png" title="YouTube" /></a>
			</div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-2 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white" style="font-size:x-small;">Copyright &copy; Your Website 2019 (<a href="https://github.com/KuJoe/podcastpanel" target="_blank" />!</a>)<br />Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a></p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom JavaScript for this theme -->
  <script src="assets/js/scrolling-nav.js"></script>
  
  <!-- JavaScript for audio player (https://github.com/greghub/green-audio-player) -->
  <script src="assets/js/green-audio-player.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new GreenAudioPlayer('.latest-episode', { showTooltips: true, showDownloadButton: true, enableKeystrokes: true });
    });
  </script>

</body>
</html>