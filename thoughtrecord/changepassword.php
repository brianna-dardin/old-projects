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

if ( isset($_POST['btn-submit']) ) {
	$current = $_POST['current'];
	$new = $_POST['newpass'];
	$confirm = $_POST['confirm'];
	
	$current = hash('sha256', $current);
	
	if($current == $userRow['Password']) {
		$new = hash('sha256', $new);
		$query = "UPDATE user SET Password = '$new' WHERE ID = $userID";
		$pass = $db->query($query);
		if($pass) {
			$errTyp = "success";
			$errMSG = "Password successfully changed.";
			unset($current);
			unset($new);
			unset($confirm);
	   } else {
		$errTyp = "danger";
		$errMSG = "Something went wrong, try again later..."; 
	   } 
	}
}

if ( isset($_POST['btn-cancel']) ) {
	header('Location: profile.php');
  }
 
$title = 'Change Password';
require_once 'header.php'; ?>

<div class="container" id="wrapper">
	<div class="row">
		<div class="col-lg-12">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
				<div class="form-group">
					 <h2 class="">Change Password</h2>
					</div>
				
				 <div class="form-group">
					 <hr />
					</div>
				<?php
				   if ( isset($errMSG) ) {
					
					?>
					<div class="form-group">
							 <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
					<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
								</div>
							 </div>
								<?php
				   }
				   ?>
				<div class="form-group">
					<label for="current">Current Password</label>
					<input type="password" name="current" id="current" class="form-control" placeholder="Enter Current Password" maxlength="15" />
				</div>
				<div class="form-group">
					<label for="new">New Password</label>
					<input type="password" name="newpass" id="newpass" class="form-control" placeholder="Enter New Password" maxlength="15" />
				</div>
				<div class="form-group">
					<label for="confirm">Confirm Password</label>
					<input type="password" name="confirm" id="confirm" class="form-control" placeholder="Confirm New Password" maxlength="15" />
				</div>
				<div class="form-group">
				 <button type="submit" class="btn btn-block btn-primary" name="btn-submit">Submit</button>
				</div>
				<div class="form-group">
				 <button type="submit" class="btn btn-block btn-primary cancel" name="btn-cancel">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php require_once 'footer.php'; ?>