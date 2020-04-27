<?php
	session_start();
	if(!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}
	require('../inc/functions.php');
	require('../inc/config.php');
	require('../inc/mp3class.php');
	if($_POST) {
		$uploadOk = 1;
		$epfile_name = str_replace("'", "", $_FILES['filename']['name']);
		$epfile_size = $_FILES['filename']['size'];
		$epext = strtolower(pathinfo($epfile_name, PATHINFO_EXTENSION));
		$valid_epext = array("mp3");
		if(in_array($epext, $valid_epext)) {
			$path = '../'.$target_dir.'/'.$epfile_name;
			if(!move_uploaded_file($_FILES['filename']['tmp_name'],$path)) {
				$uploadOk = 0;
				$err = "Episode upload failed.";
			}
		}
		$mp3file = new MP3File($path);
		$duration = $mp3file->getDuration();
		$epfile_duration = MP3File::formatTime($duration);
		$artfile_name = str_replace("'", "", $_FILES['art']['name']);
		$artext = strtolower(pathinfo($artfile_name, PATHINFO_EXTENSION));
		$valid_artext = array("png","jpeg","jpg","gif");
		if(in_array($artext, $valid_artext)) {	
			$path = '../'.$target_dir.'/'.$artfile_name;
			if(!move_uploaded_file($_FILES['art']['tmp_name'],$path)) {
				$uploadOk = 0;
				$err = "Art upload failed.";
			}
		}
		if($uploadOk = 1) {
			$run = addep($_POST['epname'], $_POST['epdesc'], $epfile_name, $epfile_size, $epfile_duration, $_POST['tags'], $artfile_name, $_SESSION['uid']);
			if($run === true) {
				$msg = "Episode added.";
			} else {
				$err = $run;
			}
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

  <title>Add Episode - Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/simple-sidebar.css" rel="stylesheet">

</head>

<body>

  <div class="d-flex" id="wrapper">

    <?php include('nav.php'); ?>

      <div class="container-fluid">
        <h1 class="mt-4">Add Episode</h1>
		<div class="d-flex justify-content-center">
			<div class="col-md-4">
				<table class="table table-bordered">
					<tbody>
						<form action="" method="post" enctype="multipart/form-data" >
						<tr>
							<td class="text-right align-middle"><label>Episode Name:</label><span id="epname-info" class="info"></span></td>
							<td><input type="text" name="epname" id="epname"></td>
						</tr>
						<tr>
							<td class="text-right align-middle"><label>Episode Description:</label><span id="epdesc-info" class="info"></span></td>
							<td><textarea name="epdesc" id="epdesc" cols="43" rows="3"></textarea></td>
						</tr>
						<tr>
							<td class="text-right align-middle"><label>Tags:</label><span id="tags-info" class="info"></span></td>
							<td><input type="text" name="tags" id="tags"></td>
						</tr>
						<tr>
							<td class="text-right align-middle"><label>File:</label><span id="filename-info" class="info"></span></td>
							<td><input type="file" name="filename" id="filename"></td>
						</tr>
						<tr>
							<td class="text-right align-middle"><label >Cover Art:</label><span id="art-info" class="info"></span></td>
							<td><input type="file" name="art" id="art"></td>
						</tr>
						<tr>
							<td colspan="2"><input style="display: block;margin: auto;" type="submit" value="Add Episode" /></button></td>
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