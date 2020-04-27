<?php
	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		header('Location: episodes.php');
		exit;
	}
	
	if($_POST['epname']) {
		$run = editep($id, $_POST['epname'], $_POST['epdesc'], $_POST['tags'], $_POST['order'], $_SESSION['uid']);
		if($run === true) {
			$msg = "Episode edited.";
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
	if($_POST['toggle']) {
		$run = toggleState($_GET['id'],'episodes',$_SESSION['uid']);
		if($run === true) {
			$msg = "Episode status has been updated.";
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
	$ep = getEpisode($id);
	if($ep['status'] == 'Hidden') {
		$statemsg = 'Publish Episode';
	} else {
		$statemsg = 'Hide Episode';
	}		
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Manage Episode - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Manage Episodes</h1>
			<div class="d-flex justify-content-center">
				<div class="col-md-4">
					<table class="table table-bordered">
						<tbody>
							<form action="" method="post" >
							<tr>
								<td class="text-right align-middle">Episode Name</td><td><input type="text" name="epname" id="epname" value="<?php echo $ep['epname']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Episode Description</td><td><textarea name="epdesc" id="epdesc" cols="43" rows="3" ><?php echo $ep['epdesc']?></textarea></td>
							</tr>
							<tr>
								<td class="text-right align-middle">File Size</td><td><?php echo $ep['size']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Duration</td><td><?php echo $ep['duration']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Filename</td><td><?php echo $ep['filename']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Uploaded</td><td><?php echo $ep['upldate']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Publish Date</td><td><?php echo $ep['publishdate']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Status</td><td><?php echo $ep['status']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Tags</td><td><input type="text" name="tags" id="tags" value="<?php echo $ep['tags']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Cover Art</td><td><?php echo $ep['art']?></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Order</td><td><input type="text" name="order" id="order" value="<?php echo $ep['sortorder']?>"></td>
							</tr>
							<tr>
								<td class="text-right align-middle">Uploaded By</td><td><?php echo $ep['ipaddr']?></td>
							</tr>
							<tr>
								<td colspan="2"><input style="display: block;margin: auto;" type="submit" value="Edit Episode" /></button></td>
							</tr>
							</form>
						</tbody>
					</table>
					<form action="" method="post" >
						<input type="hidden" id="toggle" name="toggle" value="1">
						<input style="display: block;margin: auto;" type="submit" value="<?php echo $statemsg; ?>" />
					</form>
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