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
  
  // clean user inputs to prevent sql injections
  $name = clean($_POST['name']);
  $email = clean($_POST['email']);
  $country = $_POST['country'];
  $zone = $_POST['zone'];
  
	$query = "UPDATE user SET Username = '$name', Email = '$email', country_code = '$country', zone_id = '$zone' WHERE ID = $userID";
	$res = $db->query($query);

	if ($res) {
	$errTyp = "success";
	$errMSG = "Successfully updated your profile.";
	$user=$db->query("SELECT * FROM user WHERE ID=$userID");
	$userRow=$user->fetch_assoc();
	unset($name);
	unset($email);
	unset($pass);
	} else {
	$errTyp = "danger";
	$errMSG = "Something went wrong, try again later..."; 
	} 
 }
 
 if ( isset($_POST['btn-cancel']) ) {
	header('Location: profile.php');
  }
 
$title = 'Change Profile';
require_once 'header.php';
?>

<div class="container" id="wrapper">
	<div class="row">
	<div class="col-lg-12">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
         <div class="form-group">
			 <h2 class="">Change Profile</h2>
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
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="name" class="form-control" maxlength="50" value="<?php echo $userRow['Username']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="email" class="form-control" maxlength="40" value="<?php echo $userRow['Email']; ?>" />
                </div>
            </div>
			
			<div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
				<select class="form-control" name="country" id="country">
				<?php
					$result=$db->query("SELECT * FROM country ORDER BY country_name ASC");
					while ($row = $result->fetch_assoc()) {
						if($userRow['country_code'] == $row['country_code']) {
							$attr = "selected";
						} else {
							$attr = "";
						}
						echo "<option value='".$row['country_code']."' ".$attr.">".$row['country_name']."</option>";
					}?>
				</select>
            </div>
			</div>
			
			<div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				<select class="form-control" name="zone" id="zone" data-userzone="<?php echo $userRow['zone_id']; ?>">
				</select>
            </div>
			</div>
			
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-submit">Submit</button>
            </div>
			
			<div class="form-group">
			 <button type="submit" class="btn btn-block btn-primary" name="btn-cancel">Cancel</button>
			</div>
    </form>
    </div> 
	</div>
</div>

<?php require_once 'footer.php'; ?>