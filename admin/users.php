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
	if(isset($_GET['id'])) {
		$run = toggleState($_GET['id'],'accounts',$_SESSION['uid']);
		if($run === true) {
			$msg = "User updated.";
		} else {
			$err = $run;
		}
		
		if($err) {
			echo '<div class="alert alert-danger" role="alert">'.$err.'</div>';
		}
		if($msg) {
			echo '<div class="alert alert-success" role="alert">'.$msg.'</div>';
		}
	}
	$total_pages = pagination($pageno,'accounts');
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Manage Users - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Manage Users</h1>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Username</th>
					<th scope="col">E-mail</th>
					<th scope="col">Display Name</th>
					<th scope="col">Title</th>
					<th scope="col">Avatar</th>
					<th scope="col">Status</th>
					<th scope="col">Created</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php getUsers($pageno); ?>
				<tr>
					<td colspan="8" style="text-align:center;"><a href="adduser.php">Add New User</a></td>
				</tr>
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