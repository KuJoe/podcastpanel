<?php

require('./inc/config.php');
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

if($conn->connect_error){
	$err = 'Failed to connect to MySQL (make sure you filled out the database info in /inc/config.php).<br />Error message: '.$conn->connect_error;
	die($err); 
} else {
	echo "Connected.\n";
}

$accounts = "CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `displayname` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ipaddr` varchar(46) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if(mysqli_query($conn, $accounts)){
	echo "Accounts table created.<br />";
} else {
	echo "Accounts table was not created.<br />";
}

$comments = "CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epid` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `comment` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `twitter` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ipaddr` varchar(46) NOT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if(mysqli_query($conn, $comments)){
	echo "Comments table created.<br />";
} else {
	echo "Comments table was not created.<br />";
}

$episodes = "CREATE TABLE `episodes` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `epname` varchar(150) NOT NULL,
  `epdesc` varchar(1000) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `size` int(8) NOT NULL,
  `duration` time NOT NULL,
  `upldate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publishdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  `tags` text NOT NULL,
  `art` varchar(50) NOT NULL,
  `sortorder` int(4) NOT NULL,
  `ipaddr` varchar(46) NOT NULL,
  primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if(mysqli_query($conn, $episodes)){
	echo "Episodes table created.<br />";
} else {
	echo "Episodes table was not created.<br />";
}

$logs = "CREATE TABLE `logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `ipaddr` varchar(46) NOT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key (logid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if(mysqli_query($conn, $logs)){
	echo "Logs table created.<br />";
} else {
	echo "Logs table was not created.<br />";
}

$userchk = mysqli_query($conn, "SELECT * FROM accounts");
if(mysqli_num_rows($userchk) > 0){
	$err = "Users exist.";
	$conn->close();
	die($err);
}

$pwd = bin2hex(openssl_random_pseudo_bytes(4));
$options = [
	'cost' => 9,
];
$password = password_hash($pwd, PASSWORD_DEFAULT, $options);
$user = 'Admin';
$email = 'test@example.com';
$stmt = $conn->prepare("INSERT INTO `accounts` (username, password, email) VALUES (?,?,?)");
$stmt->bind_param("sss", $user, $password, $email);

if($stmt->execute()){
	echo "<hr />New account created (copy down the password)!<br />Username: Admin<br />Password: ".$pwd."<br />";
	$stmt->close();
	$conn->close();	
} else {
	echo "New account was not created, please try again.<br />";
	$stmt->close();
	$conn->close();	
}


?>