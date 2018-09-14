<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // if session is not set this will redirect to login page
$userID = $_SESSION['user'];
 
 if( !isset($userID) ) {
  header("Location: index.php");
  exit;
 }
 
// select loggedin users detail
$user=$db->query("SELECT * FROM user WHERE ID=$userID");
$userRow=$user->fetch_assoc();
 
$title = 'Profile';
require_once 'header.php'; ?>

<div class="container" id="wrapper">
	<div class="row">
		<div class="col-lg-6">
			<h4>Your Profile</h4>
			<dl class="row">
			  <dt class="col-sm-2">Name</dt>
			  <dd class="col-sm-10"><?php echo $userRow['Username']; ?></dd>
			</dl>
			<dl class="row">
			  <dt class="col-sm-2">Email</dt>
			  <dd class="col-sm-10"><?php echo $userRow['Email']; ?></dd>
			</dl>
			<dl class="row">
			  <dt class="col-sm-2">Country</dt>
			  <dd class="col-sm-10">
				<?php 
					$res1=$db->query("SELECT country_name FROM country WHERE country_code = '".$userRow['country_code']."'");
					$country = $res1->fetch_assoc();
					echo $country['country_name']; ?></dd>
			</dl>
			<dl class="row">
			  <dt class="col-sm-2">Timezone</dt>
			  <dd class="col-sm-10">
				<?php 
					$res2 = $db->query("SELECT zone_name FROM zone WHERE zone_id = ".$userRow['zone_id']);
					$zone = $res2->fetch_assoc(); 
					echo $zone['zone_name']; ?></dd>
			</dl>
		</div>
		<div class="col-lg-6">
			<h4>Update Profile</h4>
			<p class="lead">
				<a class="btn btn-primary" href="changeprofile.php" role="button">Change Profile</a>
			</p>
			<p class="lead">
				<a class="btn btn-primary" href="changepassword.php" role="button">Change Password</a>
			</p>
		</div>
	</div>
</div>

<?php require_once 'footer.php'; ?>