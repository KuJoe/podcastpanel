<?php

/** By KuJoe (KuJoe.net) **/

function getRealUserIp(){
	switch(true){
		case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
		case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
		case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
		default : return $_SERVER['REMOTE_ADDR'];
	}
}

function clean_var($variable) {
	$variable = strip_tags( stripslashes( trim( rtrim( $variable ) ) ) );
	return $variable;
}

function logit($uid,$msg) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	
	$ipaddr = getRealUserIp();
		
	$stmt = $conn->prepare("INSERT INTO `logs` (msg, uid, ipaddr) VALUES (?,?,?)");
	$stmt->bind_param("sis", $msg, $uid, $ipaddr);

	if($stmt->execute()){
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		return false;
	}
}

function updateIP($uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	
	$ipaddr = getRealUserIp();
		
	$stmt = $conn->prepare("UPDATE `accounts` SET ipaddr = ? WHERE id = ?");
	$stmt->bind_param("si", $ipaddr, $uid);

	if($stmt->execute()){
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		return false;
	}
}

function addep($epname, $epdesc, $filename, $size, $duration, $tags, $art, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();	
		return $err;
	}
	
	$ipaddr = getRealUserIp();
	$epname = clean_var($epname);
	$epdesc = clean_var($epdesc);
	$filename = clean_var($filename);
	$tags = clean_var($tags);
	$art = clean_var($art);
	
	$filechk = mysqli_query($conn, "SELECT filename FROM episodes WHERE filename='$filename'");
    if(mysqli_num_rows($filechk) > 0) {
		$err = "File exists, delete the old episode or rename the file.";
		$conn->close();	
		return $err;
    }
	
	$nextso = mysqli_query($conn, "SELECT sortorder FROM episodes ORDER BY sortorder DESC LIMIT 1");
    if(mysqli_num_rows($nextso) > 0) {
		$row = mysqli_fetch_assoc($nextso);
		$next = $row['sortorder'] + 1;
    } else {
		$next = "1";
	}
		
	$stmt = $conn->prepare("INSERT INTO `episodes` (epname, epdesc, filename, size, duration, tags, art, sortorder, ipaddr) VALUES (?,?,?,?,?,?,?,?,?)");
	
	$stmt->bind_param("sssssssis", $epname, $epdesc, $filename, $size, $duration, $tags, $art, $next, $ipaddr);
	
	$id = mysqli_query($conn, "SELECT id FROM episodes WHERE filename = '$filename' ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_assoc($id);

	if($stmt->execute()){
		$msg = 'New episode added! ('.$row['id'].' - '.$epname.')';
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$err = 'Unable to add episode to database ('.$stmt->error.')';
		logit($uid,$err);
		$stmt->close();
		$conn->close();
		return $err;
	}
	
}

function editep($id, $epname, $epdesc, $tags, $order, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();	
		return $err;
	}
	
	$epname = clean_var($epname);
	$epdesc = clean_var($epdesc);
	$tags = clean_var($tags);
	
	$stmt = $conn->prepare("UPDATE `episodes` SET epname = ?, epdesc = ?, tags = ?, sortorder = ? WHERE id = ?");
	
	$stmt->bind_param("sssii", $epname, $epdesc, $tags, $order, $id);
	
	if($stmt->execute()){
		$msg = 'Episode edited! ('.$id.' - '.$epname.')';
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		$err = 'Unable to edit episode in the database ('.$id.')';
		logit($uid,$err);
		return $err;
	}
}

function getEpisode($id){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes WHERE id='$id' LIMIT 1");
	if(mysqli_num_rows($res) > 0) {
		while ($row = $res->fetch_assoc()) {
			if($row['status'] == '1'){
				$status = "Published";
			} else {
				$status = "Hidden";
			}
			$size = round($row['size'] / 1024 / 1024,2) . 'MB';
			return array(
				'epname' 	=> $row['epname'],
				'epdesc' 	=> $row['epdesc'],
				'filename' 	=> $row['filename'],
				'size' 		=> $size,
				'duration' 	=> $row['duration'],
				'upldate'	=> $row['upldate'],
				'publishdate' => $row['publishdate'],
				'status' 	=> $status,
				'tags' 		=> $row['tags'],
				'art' 		=> $row['art'],
				'sortorder' => $row['sortorder']
			);		
		}
    } else {
		return false;
	}
	$conn->close();	
}

function getEpisodes($pageno){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$no_of_records_per_page = 10;
	$offset = ($pageno-1) * $no_of_records_per_page;
	
	$res = mysqli_query($conn, "SELECT * FROM episodes ORDER BY sortorder DESC LIMIT $offset, $no_of_records_per_page");
	while ($row = $res->fetch_assoc()) {
		if($row['status'] == '1'){
			$status = "Published";
			$img = "invisible";
		} else {
			$status = "Hidden";
			$img = "visible";
		}
		$size = round($row['size'] / 1024 / 1024,2) . 'MB';
		echo '<tr><td><a href="episode.php?id='.$row['id'].'">'.$row['epname'].'</a></td><td>'.$size.'</td><td>'.$row['duration'].'</td><td>'.$row['filename'].'</td><td>'.date("m/d/Y", strtotime($row['upldate'])).'</td><td>'.date("m/d/Y", strtotime($row['publishdate'])).'</td><td>'.$status.'</td><td><a href="episode.php?id='.$row['id'].'"><img src="../assets/cog.png" alt="edit"/></a><a href="episodes.php?id='.$row['id'].'"><img src="../assets/'.$img.'.png" alt="toggle"/></a></td></tr>';
	}
	$conn->close();	
}

function pagination($pageno,$table){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$no_of_records_per_page = 10;
	$offset = ($pageno-1) * $no_of_records_per_page;
	$total_pages_sql = "SELECT COUNT(*) FROM $table";
	$result = mysqli_query($conn,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);
	$conn->close();	
	return $total_pages;
}

function getProfile($uid){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$uid' LIMIT 1");
	if(mysqli_num_rows($res) > 0) {
		while ($row = $res->fetch_assoc()) {
			if($row['status'] == '1'){
				$status = "Active";
			} else {
				$status = "Disabled";
			}
			return array(
				'username' 	=> $row['username'],
				'email' 	=> $row['email'],
				'displayname' 	=> $row['displayname'],
				'title' 	=> $row['title'],
				'avatar'	=> $row['avatar'],
				'twitter' => $row['twitter'],
				'status' => $status,
				'created' => $row['created']
			);		
		}
    } else {
		return false;
	}
	$conn->close();	
}

function getUsername($uid){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$uid' LIMIT 1");
	if(mysqli_num_rows($res) > 0) {
		$user = mysqli_fetch_assoc($res);
		return $user['username'];
    } else {
		return 'SYSTEM';
	}
	$conn->close();	
}

function getLogs($pageno){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$no_of_records_per_page = 10;
	$offset = ($pageno-1) * $no_of_records_per_page;
	
	$res = mysqli_query($conn, "SELECT * FROM logs ORDER BY logid DESC LIMIT $offset, $no_of_records_per_page");
	while ($row = $res->fetch_assoc()) {
		echo '<tr><td>'.$row['msg'].'</td><td><a href="profile.php?id='.$row['uid'].'">'.getUsername($row['uid']).'</a></td><td>'.$row['ipaddr'].'</td><td>'.date("m/d/Y", strtotime($row['tstamp'])).'</td></tr>';
	}
	$conn->close();	
}

function getUsers($pageno){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$no_of_records_per_page = 10;
	$offset = ($pageno-1) * $no_of_records_per_page;
	
	$res = mysqli_query($conn, "SELECT * FROM accounts ORDER BY id ASC LIMIT $offset, $no_of_records_per_page");
	while ($row = $res->fetch_assoc()) {
		if($row['status'] == '1'){
			$status = "Active";
			$img = "invisible";
		} else {
			$status = "Disabled";
			$img = "visible";
		}
		if($row['id'] > '1') {
			echo '<tr><td><a href="profile.php?id='.$row['id'].'">'.$row['username'].'</a></td><td>'.$row['email'].'</td><td>'.$row['displayname'].'</td><td>'.$row['title'].'</td><td><img src="https://www.gravatar.com/avatar/'.$row['avatar'].'?s=32" /></td><td>'.$status.'</td><td>'.date("m/d/Y", strtotime($row['created'])).'</td><td><a href="profile.php?id='.$row['id'].'"><img src="../assets/cog.png" alt="edit"/></a><a href="users.php?id='.$row['id'].'"><img src="../assets/'.$img.'.png" alt="toggle"/></a></td></tr>';
		} else {
			echo '<tr><td>'.$row['username'].'</td><td>'.$row['email'].'</td><td>'.$row['displayname'].'</td><td>'.$row['title'].'</td><td><img src="https://www.gravatar.com/avatar/'.$row['avatar'].'?s=32" /></td><td>'.$status.'</td><td>'.date("m/d/Y", strtotime($row['created'])).'</td><td></td></tr>';
		}
	}
	$conn->close();	
}

function getComments($pageno){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$no_of_records_per_page = 10;
	$offset = ($pageno-1) * $no_of_records_per_page;
	
	$res = mysqli_query($conn, "SELECT * FROM comments ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");
	while ($row = $res->fetch_assoc()) {
		if($row['status'] == '1'){
			$status = "Active";
			$img = "invisible";
		} else {
			$status = "Hidden";
			$img = "visible";
		}
		$avatar = md5(strtolower(trim($row['email'])));
		echo '<tr><td>'.$row['author'].' (<a href="https://twitter.com/'.$row['twitter'].'" target="_blank">'.$row['twitter'].'</a>)<br />('.$row['email'].')</td><td>'.$row['comment'].'</td><td><a href="episode.php?id='.$row['epid'].'">'.$row['epid'].'</a></td><td><img src="https://www.gravatar.com/avatar/'.$avatar.'?s=32" /></td><td>'.$status.'</td><td>'.$row['ipaddr'].'</td><td>'.date("m/d/Y", strtotime($row['tstamp'])).'</td><td><a href="comments.php?id='.$row['id'].'"><img src="../assets/'.$img.'.png" alt="toggle"/></a></td></tr>';
	}
	$conn->close();	
}

function toggleState($id, $table, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();
		return $err;
	}

	if($table == 'accounts' AND $id == '1'){
		$err = "Unable to change the state of the original user.";
		$conn->close();
		return $err;
	}
	
	$state = mysqli_query($conn, "SELECT status FROM $table WHERE id = '$id' LIMIT 1");
	$row = mysqli_fetch_assoc($state);
	
	if($row['status'] == '0') {
		$newstate = '1';
	} else {
		$newstate = '0';
	}
		
	$stmt = $conn->prepare("UPDATE `$table` SET status = ? WHERE id = ?");
	
	$stmt->bind_param("ii", $newstate, $id);
	
	if($stmt->execute()){
		if($newstate == '1') {
			$msg = 'Set '.substr($table, 0, -1).' to active! ('.$id.')';
		} else {
			$msg = 'Set '.substr($table, 0, -1).' to hidden! ('.$id.')';
		}
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		$err = 'Unable to update the $table status ('.$id.')';
		logit($uid,$err);
		return $err;
	}
	
}

function adduser($username, $email, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();	
		return $err;
	}
	
	$username = clean_var($username);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  $err = "Invalid email format";
	  return $err;
	}
	
	$userchk = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$username' OR email='$email'");
    if(mysqli_num_rows($userchk) > 0) {
		$err = "User exists, try a different username or e-mail address.";
		$conn->close();	
		return $err;
    }
	
	$pwd = bin2hex(openssl_random_pseudo_bytes(4));
	$options = [
		'cost' => 9,
	];
	$password = password_hash($pwd, PASSWORD_DEFAULT, $options);
	$avatar = md5(strtolower(trim($email)));
	
	$stmt = $conn->prepare("INSERT INTO `accounts` (username, password, email, avatar) VALUES (?,?,?,?)");
	
	$stmt->bind_param("ssss", $username, $password, $email, $avatar);
	
	$uid = mysqli_query($conn, "SELECT id FROM accounts WHERE username = '$username' ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_assoc($uid);

	if($stmt->execute()){
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <'.$fromemail.'>. \r\n';
		
		$message = '<p>A new user account was created for you.<br />
					You can login with the following information:<br >
					Admin Panel: <a href="https://'.$_SERVER['HTTP_HOST'].'/'.$admindir.'/" target="_blank">https://'.$_SERVER['HTTP_HOST'].'/'.$admindir.'</a><br />
					Username: '.$username.'<br />
					Password: '.$pwd.'</p>';
		
		mail($email,'New user information',$message,$headers);
		$msg = 'New user added! ('.$row['id'].' - '.$username.' - '.$pwd.')';
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		$err = 'Unable to add user to database ('.$id.')';
		logit($uid,$err);
		return $err;
	}
	
}

function edituser($id, $username, $newemail, $displayname, $title, $twitter, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();	
		return $err;
	}
	
	if (!filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
	  $err = "Invalid email format";
	  return $err;
	}
	
	$userchk = mysqli_query($conn, "SELECT * FROM accounts WHERE (username='$username' OR email='$newemail') AND id != '$id'");
    if(mysqli_num_rows($userchk) > 0) {
		$err = "User exists, try a different username or e-mail address.";
		$conn->close();	
		return $err;
    }

	$avatar = md5(strtolower(trim($newemail)));
	
	$stmt = $conn->prepare("UPDATE `accounts` SET username = ?, email = ?, displayname = ?, title = ?, twitter = ?, avatar = ? WHERE id = ?");
	
	$stmt->bind_param("ssssssi", $username, $newemail, $displayname, $title, $twitter, $avatar, $id);
	
	if($stmt->execute()){
		$msg = 'User edited! ('.$id.' - '.$username.')';
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		$err = 'Unable to edit user in the database ('.$id.')';
		logit($uid,$err);
		return $err;
	}
}

function chgpass($id, $username, $pwd, $uid) {
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
		$err = "Failed to connect to MySQL: " . $conn->connect_error;
		$conn->close();	
		return $err;
	}
	
	$options = [
		'cost' => 9,
	];
	$password = password_hash($pwd, PASSWORD_DEFAULT, $options);
	
	$stmt = $conn->prepare("UPDATE `accounts` SET password = ? WHERE id = ? AND username = ?");
	
	$stmt->bind_param("sis", $password, $id, $username);
	
	if($stmt->execute()){
		$msg = 'User password updated! ('.$id.' - '.$username.')';
		logit($uid,$msg);
		$stmt->close();
		$conn->close();	
		return true; 
	}else{
		$stmt->close();
		$conn->close();
		$err = 'Unable to update user password in the database ('.$id.')';
		logit($uid,$err);
		return $err;
	}
}

function latestComments(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM comments ORDER BY id DESC LIMIT 5");
	while ($row = $res->fetch_assoc()) {
		if($row['status'] == '1'){
			$status = "Active";
		} else {
			$status = "Hidden";
		}
		$avatar = md5(strtolower(trim($row['email'])));
		echo '<tr><td>'.$row['author'].' (<a href="https://twitter.com/'.$row['twitter'].'" target="_blank">'.$row['twitter'].'</a>)<br />('.$row['email'].')</td><td>'.$row['comment'].'</td><td><a href="episode.php?id='.$row['epid'].'">'.$row['epid'].'</a></td><td><img src="https://www.gravatar.com/avatar/'.$avatar.'?s=32" /></td><td>'.$status.'</td><td>'.$row['ipaddr'].'</td><td>'.date("m/d/Y", strtotime($row['tstamp'])).'</td><td><a href="comments.php"><img src="../assets/cog.png" alt="manage"/></a></td></tr>';
	}
	$conn->close();	
}

function latestEpisodes(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes ORDER BY id DESC LIMIT 5");
	while ($row = $res->fetch_assoc()) {
		if($row['status'] == '1'){
			$status = "Published";
			$img = "invisible";
		} else {
			$status = "Hidden";
			$img = "visible";
		}
		$size = round($row['size'] / 1024 / 1024,2) . 'MB';
		echo '<tr><td><a href="episode.php?id='.$row['id'].'">'.$row['epname'].'</a></td><td>'.$size.'</td><td>'.$row['duration'].'</td><td>'.$row['filename'].'</td><td>'.date("m/d/Y", strtotime($row['upldate'])).'</td><td>'.date("m/d/Y", strtotime($row['publishdate'])).'</td><td>'.$status.'</td><td><a href="episode.php?id='.$row['id'].'"><img src="../assets/cog.png" alt="edit"/></a></td></tr>';
	}
	$conn->close();	
}

function latestLogs(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
		
	$res = mysqli_query($conn, "SELECT * FROM logs ORDER BY logid DESC LIMIT 5");
	while ($row = $res->fetch_assoc()) {
		echo '<tr><td>'.$row['msg'].'</td><td><a href="profile.php?id='.$row['uid'].'">'.getUsername($row['uid']).'</a></td><td>'.$row['ipaddr'].'</td><td>'.date("m/d/Y", strtotime($row['tstamp'])).'</td></tr>';
	}
	$conn->close();	
}

function getep($id){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes WHERE id='$id' AND status='1'");
	if(mysqli_num_rows($res) > 0) {
		$out = mysqli_fetch_assoc($res);
		$size = round($out['size'] / 1024 / 1024,2) . 'MB';
		$ep['id'] = $id;
		$ep['epname'] = $out['epname'];
		$ep['epdesc'] = $out['epdesc'];
		$ep['duration'] = $out['duration'];
		$ep['size'] = $size;
		$ep['filename'] = $out['filename'];
		$ep['publishdate'] = $out['publishdate'];
		$ep['art'] = $out['art'];
		return $ep;
	} else {
		return false;
	}
	$conn->close();	
}

function latestEp(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes WHERE status='1' ORDER BY sortorder DESC LIMIT 1");
	if(mysqli_num_rows($res) > 0) {
		$out = mysqli_fetch_assoc($res);
		$size = round($out['size'] / 1024 / 1024,2) . 'MB';
		$latest['id'] = $out['id'];
		$latest['epname'] = $out['epname'];
		$latest['epdesc'] = $out['epdesc'];
		$latest['duration'] = $out['duration'];
		$latest['size'] = $size;
		$latest['filename'] = $out['filename'];
		$latest['publishdate'] = $out['publishdate'];
		$latest['art'] = $out['art'];
		return $latest;
	} else {
		return false;
	}
	$conn->close();	
}

function latestEpID(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes WHERE status='1' ORDER BY sortorder DESC LIMIT 1");
	if(mysqli_num_rows($res) > 0) {
		$out = mysqli_fetch_assoc($res);
		return $out['id'];
	} else {
		return false;
	}
	$conn->close();	
}

function listEpisodes(){
	require('config.php');
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error){
	  echo "Failed to connect to MySQL: " . $conn->connect_error;
	}
	
	$res = mysqli_query($conn, "SELECT * FROM episodes WHERE status='1' ORDER BY sortorder ASC");
	if(mysqli_num_rows($res) > 0) {
		while ($row = $res->fetch_assoc()) {
			echo '<tr><td><a href="episode.php?id='.$row['id'].'">'.$row['epname'].'</a></td><td>'.$row['epdesc'].'</td><td>'.date("m/d/Y", strtotime($row['publishdate'])).'</td></tr>';
		}
	} else {
		echo '<tr><td colspan="5" style="text-align:center;"><strong>Nothing uploaded yet.</strong></td></tr>';
	}
	$conn->close();	
}

?>