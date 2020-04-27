<?php
	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');

	if(isset($_GET['p'])) {
		$pageno = $_GET['p'];
	} else {
		$pageno = 1;
	}
	$total_pages = pagination($pageno,'logs');
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Logs - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">View Logs</h1>
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
				<?php getLogs($pageno); ?>
			</tbody>
		</table>
		<div class="d-flex justify-content-center">
			<div class="col-md-2">
				<nav aria-label="Page navigation example">
					<ul class="pagination">
						<li class="page-item"><a class="page-link" href="?p=1">First</a></li>
						<li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
							<a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?p=".($pageno - 1); } ?>">Prev</a>
						</li>
						<li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
							<a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?p=".($pageno + 1); } ?>">Next</a>
						</li>
						<li class="page-item"><a class="page-link" href="?p=<?php echo $total_pages; ?>">Last</a></li>
					</ul>
				</nav>
			</div>
		</div>
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