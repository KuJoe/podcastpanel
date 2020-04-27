<?php

/** By KuJoe (KuJoe.net) **/

	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Dashboard - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <p><h3 class="mt-4">Latest Comments</h3>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Author</th>
					<th scope="col">Comment</th>
					<th scope="col">Episode</th>
					<th scope="col">Avatar</th>
					<th scope="col">Status</th>
					<th scope="col">IP Address</th>
					<th scope="col">Timestamp</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php echo latestComments(); ?></p>
			</tbody>
		</table>
		<p><h3 class="mt-4">Latest Episodes</h3>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Episode</th>
					<th scope="col">File Size</th>
					<th scope="col">Duration</th>
					<th scope="col">Filename</th>
					<th scope="col">Uploaded</th>
					<th scope="col">Publish Date</th>
					<th scope="col">Status</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php echo latestEpisodes(); ?></p>
			</tbody>
		</table>
		<p><h3 class="mt-4">Latest Logs</h3>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Log Message</th>
					<th scope="col">User ID</th>
					<th scope="col">IP Address</th>
					<th scope="col">Timestamp</th>
				</tr>
			</thead>
			<tbody>
				<?php echo latestLogs(); ?></p>
			</tbody>
		</table>
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>

</body>

</html>
