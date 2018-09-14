<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: home.php");
 }
 include_once 'dbconnect.php';

 if ( isset($_POST['btn-signup']) ) {
  
  // clean user inputs to prevent sql injections
  $name = clean($_POST['name']);
  $email = clean($_POST['email']);
  $pass = clean($_POST['newpass']);
  $country = $_POST['country'];
  $zone = $_POST['zone'];
  
  // password encrypt using SHA256();
  $password = hash('sha256', $pass);
   
   $query = "INSERT INTO user(Username,Email,Password,country_code,zone_id) VALUES('$name','$email','$password','$country','$zone')";
   $res = $db->query($query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
	
	require_once "../PHPMailer/PHPMailerAutoload.php";
	
	$mail = new PHPMailer;
	$mail->setFrom('from@briannadardin.com', 'Brianna at Thought Record');
	$mail->addAddress($email, $name);
	$mail->addReplyTo('brianna.dardin@gmail.com', 'Brianna Dardin');
	$mail->isHTML(true);
	$mail->Subject = 'Thank you for signing up on Thought Record!';
	$mail->Body    = "Hi $name,<br/><br/>Thank you so much for signing up on Thought Record! Now you can <b><a href='http://briannadardin.com/thoughts/signin.php'>log in</a></b> and start posting your thoughts! :)<br/><br/>If you have any questions, reply to this email and I'll get back to you ASAP!<br/><br/>Sincerely,<br/>Brianna Dardin";
	if(!$mail->send()) {
		error_log('Message could not be sent.');
		error_log('Mailer Error: ' . $mail->ErrorInfo);
	} 
	
	$mail2 = new PHPMailer;
	$mail2->setFrom('from@briannadardin.com', 'Thought Record');
	$mail2->addAddress('brianna.dardin@gmail.com');
	$mail2->Subject = 'Thought Record: New User';
	$mail2->Body    = "Someone has signed up on Thought Record! Here are their details:\n\nName: $name\nEmail: $email\nCountry: $country\nTimezone: $zone";
	if(!$mail2->send()) {
		error_log('Message could not be sent.');
		error_log('Mailer Error: ' . $mail->ErrorInfo);
	} 
	
    unset($name);
    unset($email);
    unset($pass);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later..."; 
   } 
    
  }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Thought Record - Sign Up</title>
<link rel="stylesheet" href="bootstrap.min.css" type="text/css"  />
</head>
<body>

<div class="container">

 <div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
     <div class="col-md-12">
        
         <div class="form-group">
             <h2 class="">Sign Up.</h2>
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
             <input type="text" name="name" class="form-control" placeholder="Enter Name" maxlength="50" value="<?php echo $name ?>" />
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="newpass" id="newpass" class="form-control" placeholder="Enter Password" maxlength="15" />
                </div>
            </div>
			
			<div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="confirm" id="confirm" class="form-control" placeholder="Confirm Password" maxlength="15" />
                </div>
            </div>
			
			<div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
				<select class="form-control" name="country" id="country">
				<?php
					$result=$db->query("SELECT * FROM country ORDER BY country_name ASC");
					while ($row = $result->fetch_assoc()) {
						echo "<option value='".$row['country_code']."'>".$row['country_name']."</option>";
					}?>
				</select>
            </div>
			</div>
			
			<div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
				<select class="form-control" name="zone" id="zone">
				</select>
            </div>
			</div>
			
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
            </div>
            
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <a href="signin.php">Sign in Here...</a>
            </div>
        
        </div>
   
    </form>
    </div> 

</div>

<?php require_once 'footer.php'; ?>