<?php
	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		if($id == '1') {
			header('Location: users.php');
			exit;
		}
	} else {
		$id = $_SESSION['uid'];
	}
	
	if($_POST) {
		$run = edituser($id, $_POST['username'], $_POST['email'], $_POST['displayname'], $_POST['title'], $_POST['twitter'], $_SESSION['uid']);
		if($run === true) {
			$msg = "User edited.";
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
	$pro = getProfile($id);
	if(!$pro['username']){
		header('Location: users.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Profile - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Profile</h1>
			<div class="d-flex justify-content-center">
				<div class="col-md-4">
					<table class="table table-bordered">
						<tbody>
							<form action="" method="post" >
							<tr>
								<td class="text-right align-middle">Username</td><td><input type="text" name="username" id="username" value="<?php echo $pro['username']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">E-mail</td><td><input type="email" name="email" id="email" value="<?php echo $pro['email']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Display Name</td><td><input type="text" name="displayname" id="displayname" value="<?php echo $pro['displayname']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">User Title</td><td><input type="text" name="title" id="title" value="<?php echo $pro['title']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Avatar</td><td class="align-middle"><img src="https://www.gravatar.com/avatar/<?php echo $pro['avatar']?>" /> <small><a href="http://en.gravatar.com/support/activating-your-account/" target="_blank">Change Gravatar</a></small></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Twitter Handle</td><td><input type="text" name="twitter" id="twitter" value="<?php echo $pro['twitter']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Status</td><td><?php echo $pro['status']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Created</td><td><?php echo $pro['created']?></td>
							</tr>
							<tr>
								<td colspan="2"><input style="display: block;margin: auto;" type="submit" value="Edit User" /></button></td>
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