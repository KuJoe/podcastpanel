<?php
	/*!
	* Start Bootstrap - Scrolling Nav (https://startbootstrap.com/template-overviews/scrolling-nav)
	* Copyright 2013-2019 Start Bootstrap
	* Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-scrolling-nav/blob/master/LICENSE)
	*/
	
	require('./inc/config.php');
	require('./inc/functions.php');
	$latest = latestEp();
	if(!$latest){
	 $showplayer = 'none';
	} else {
	 $showplayer = 'block';
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

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
      <a class="navbar-brand js-scroll-trigger" href="#page-top">PodcastPanel</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#page-top">Latest Episode</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#episodes">Episodes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="bg-primary text-white">
	<div class="container text-center">
		<h1>Welcome to PodcastPanel</h1>
		<p class="lead">This is a very basic script for self hosting your own podcast.</p>
		<br />
		<div class="playerbox" style="display:<?php echo $showplayer; ?>;">
			<div class="playerboxtext">
				<h3><?php echo $latest['epname']; ?></h3>
				<?php echo $latest['epdesc']; ?>
			</div>
			<hr />
			<img class="playerboxart" src="<?php echo $target_dir; ?>/<?php echo $latest['art']; ?>" />
			<img class="playerboximg" src="assets/clock.png" title="Duration"/><?php echo $latest['duration']; ?><br />
			<img class="playerboximg" src="assets/size.png" title="File Size"/><?php echo $latest['size']; ?><br />
			<img class="playerboximg" src="assets/calendar.png" title="Publish Date"/><?php echo date("m/d/Y", strtotime($latest['publishdate'])); ?><br />
			<div class="latest-episode">
				<audio crossorigin preload="none">
					<source src="<?php echo $target_dir; ?>/<?php echo $latest['filename']; ?>" type="audio/mpeg">
				</audio>
			</div>
		</div>
	</div>
  </header>

  <section id="about">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h2>About this podcast</h2>
          <p class="lead">Tell your listeners a bit about you and your podcast here.</p>
		  <h4 style="margin-left:140px;">Host McHosterson</h4>
		  <img src="assets/profile.png" style="float:left;height:120px;width:120px;" />
          <p style="margin-left:140px;">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut feugiat consectetur mattis. Fusce fermentum elementum enim, et blandit ligula malesuada ut. Nulla dolor lorem, accumsan viverra mollis ut, luctus at purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In hac habitasse platea dictumst. Vestibulum ac cursus turpis, vel aliquet lorem. Quisque lacus leo, hendrerit nec accumsan ac, luctus non lorem. Nam turpis eros, consequat eget egestas in, vehicula at elit. Nulla hendrerit vitae leo sed malesuada.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section id="episodes" class="bg-light">
    <div class="container">
      <div class="row">
        <div class="mx-auto">
          <p class="lead">
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th scope="col">Episode</th>
						<th scope="col">Description</th>
						<th scope="col">Published</th>
					</tr>
				</thead>
				<tbody>
					<?php listEpisodes(); ?>
				</tbody>
			</table>
		  </p>
        </div>
      </div>
    </div>
  </section>

  <section id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h2>Contact us</h2>
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero odio fugiat voluptatem dolor, provident officiis, id iusto! Obcaecati incidunt, qui nihil beatae magnam et repudiandae ipsa exercitationem, in, quo totam.</p>
        </div>
		<div class="col-lg-5 mx-auto">
			<a href="<?php echo $twitter; ?>" target="_blank" /><img class="socialicon" src="assets/twitter.png" /></a>
			<a href="<?php echo $facebook; ?>" target="_blank" /><img class="socialicon" src="assets/facebook.png" /></a>
			<a href="<?php echo $instagram; ?>" target="_blank" /><img class="socialicon" src="assets/instagram.png" /></a>
			<a href="<?php echo $snapchat; ?>" target="_blank" /><img class="socialicon" src="assets/snapchat.png" /></a>
			<a href="<?php echo $reddit; ?>" target="_blank" /><img class="socialicon" src="assets/reddit.png" /></a>
			<a href="<?php echo $tumblr; ?>" target="_blank" /><img class="socialicon" src="assets/tumblr.png" /></a>
		</div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-2 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white" style="font-size:x-small;">Copyright &copy; Your Website 2019<br />Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a></p>
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