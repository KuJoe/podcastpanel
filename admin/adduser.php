<?php
	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');
	require('../inc/config.php');
	if($_POST) {
		$run = adduser($_POST['username'], $_POST['email'], $_SESSION['uid']);
		if($run === true) {
			header('Location: users.php');
			exit;
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
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Add User - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Add User</h1>
		<div class="d-flex justify-content-center">
			<div class="col-md-4">
				<table class="table table-bordered">
					<tbody>
						<form action="" method="post" >
						<tr>
							<td class="text-right align-middle"><label>Username:</label><span id="username-info" class="info"></span></td>
							<td><input type="text" name="username" id="username"></td>
						</tr>
						<tr>
							<td class="text-right align-middle"><label>E-mail:</label><span id="email-info" class="info"></span></td>
							<td><input type="text" name="email" id="email"></td>
						</tr>
						<tr>
							<td colspan="2"><input style="display: block;margin: auto;" type="submit" value="Add User" /></button></td>
						</tr>
						</form>
					</tbody>
				</table>
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