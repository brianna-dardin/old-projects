<?php
ob_start();
session_start();
require_once 'dbconnect.php';

$userID = $_SESSION['user'];

if( !isset($userID) ) {
  header("Location: index.php");
  exit;
 }

$user=$db->query("SELECT * FROM user WHERE ID=$userID");
$userRow=$user->fetch_assoc();
 
 if ( isset($_POST['btn-submit']) ) {
	// clean user inputs to prevent sql injections
	$context = clean($_POST['context']); 
	$negThought = clean($_POST['negThought']); 
	$evidenceFor = clean($_POST['evidenceFor']); 
	$evidenceAgainst = clean($_POST['evidenceAgainst']); 
	$alternative = clean($_POST['alternative']); 
	
	$emotionBefore = $_POST['emotionBefore'];
	$emotionAfter = $_POST['emotionAfter'];
	$id = $_POST['id'];
	 
	$query = "UPDATE thought SET Context = '$context', NegativeThought = '$negThought', EvidenceFor = '$evidenceFor', EvidenceAgainst = '$evidenceAgainst', Alternative = '$alternative', EmotionBefore = '$emotionBefore', EmotionAfter = '$emotionAfter' WHERE ID = $id";
	$res = $db->query($query);
 
	if ($res) {
	unset($name);
	unset($context); 
	unset($negThought); 
	unset($evidenceFor); 
	unset($evidenceAgainst); 
	unset($alternative); 
	unset($emotionBefore);
	unset($emotionAfter);
	header('Location: view.php?id='.$id);
	} else {
	$errTyp = "danger";
	$errMSG = "Something went wrong, try again later..."; 
	} 
 }
 
  if ( isset($_POST['btn-cancel']) ) {
	header('Location: home.php');
  }

$title = 'Edit Thought';
require_once 'header.php'; ?>

<div class="container" id="wrapper">
    <div class="row">
        <div class="col-lg-12">
			<?php 
				if( isset( $_GET["id"] ) ) {
					$id = filter_input(INPUT_GET, id, FILTER_SANITIZE_ENCODED);
					$query=$db->query("SELECT * FROM thought WHERE ID=$id");
					$thought=$query->fetch_assoc();
					
					if($userID != $thought['UserID']) { ?>
					<div class="jumbotron">
						<h1 class="display-3">You don't have access to this thought.</h1>
						<p class="lead">Why not try looking at your thoughts?</p>
						<p class="lead">
							<a class="btn btn-primary btn-lg" href="home.php" role="button">Go to My Thoughts</a>
						</p>
					</div>
					<?php } else { ?>
					<h4 class="text-center"><?php 
						$time = strtotime($thought['DateUpdated']);
						$zoneQuery = $db->query("SELECT zone_name FROM zone WHERE zone_id = ". $userRow['zone_id']);
						if($zoneQuery->num_rows > 0) {
							$zone = $zoneQuery->fetch_assoc();
							$timezone = new DateTimeZone($zone['zone_name']);
							$date = new DateTime(date('m/d/Y h:i a', $time));
							$date->setTimeZone($timezone);
							echo $date->format('l F j Y g:i A');
						} else {
							$myFormatForView = date("m/d/y g:i A", $time);
							echo $myFormatForView;
						} ?></h4>
						<hr class="my-2">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
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
							<label for="context">What were you doing?</label>
							<textarea class="form-control" name="context" rows="3"><?php echo $thought['Context']; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="emotionBefore">How did you feel?</label>
							<select class="form-control" name="emotionBefore">
							  <option value="Angry" <?php echo $thought['EmotionBefore'] == "Angry" ? "selected" : "" ?>>Angry</option>
							  <option value="Anxious" <?php echo $thought['EmotionBefore'] == "Anxious" ? "selected" : "" ?>>Anxious</option>
							  <option value="Depressed" <?php echo $thought['EmotionBefore'] == "Depressed" ? "selected" : "" ?>>Depressed</option>
							</select>
						  </div>
						  <div class="form-group">
							<label for="negThought">What was your negative thought?</label>
							<textarea class="form-control" name="negThought" rows="3"><?php echo $thought['NegativeThought']; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="evidenceFor">What is the evidence for the thought?</label>
							<textarea class="form-control" name="evidenceFor" rows="3"><?php echo $thought['EvidenceFor']; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="evidenceAgainst">What is the evidence against the thought?</label>
							<textarea class="form-control" name="evidenceAgainst" rows="3"><?php echo $thought['EvidenceAgainst']; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="alternative">What is an alternative thought?</label>
							<textarea class="form-control" name="alternative" rows="3"><?php echo $thought['Alternative']; ?></textarea>
						  </div>
						  <div class="form-group">
							<label for="emotionAfter">How do you feel now?</label>
							<select class="form-control" name="emotionAfter">
							  <option value="Calm" <?php echo $thought['EmotionAfter'] == "Calm" ? "selected" : "" ?>>Calm</option>
							  <option value="Happy" <?php echo $thought['EmotionAfter'] == "Happy" ? "selected" : "" ?>>Happy</option>
							  <option value="Angry" <?php echo $thought['EmotionAfter'] == "Angry" ? "selected" : "" ?>>Angry</option>
							  <option value="Anxious" <?php echo $thought['EmotionAfter'] == "Anxious" ? "selected" : "" ?>>Anxious</option>
							  <option value="Depressed" <?php echo $thought['EmotionAfter'] == "Depressed" ? "selected" : "" ?>>Depressed</option>
							</select>
						  </div>
						  <input type="hidden" name="id" value="<?php echo $id; ?>">
						  <div class="form-group">
							 <button type="submit" class="btn btn-block btn-primary" name="btn-submit">Submit</button>
							</div>
							<div class="form-group">
							 <button type="submit" class="btn btn-block btn-primary" name="btn-cancel">Cancel</button>
							</div>
						</form>
					<?php }
					} ?>
</div> <!-- col-lg-12 -->
</div> <!-- row -->
</div> <!-- container -->

<?php require_once 'footer.php'; ?>