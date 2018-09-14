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
 
$title = 'View Thought';
require_once 'header.php'; ?>

<div class="container" id="wrapper">
    <div class="row">
        <div class="col-lg-12">
			<?php 
				if( isset( $_GET["id"] ) ) {
					$id = filter_input(INPUT_GET, id, FILTER_SANITIZE_ENCODED);
					$query=$db->query("SELECT * FROM thought WHERE ID=$id");
					$thought=$query->fetch_assoc();
					
					if($_SESSION['user'] != $thought['UserID']) { ?>
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
					<h4>What were you doing?</h4>
					<p class="mb-0"><?php echo $thought['Context']; ?></p>
					<h4>How did you feel?</h4>
					<p class="mb-0"><?php echo $thought['EmotionBefore']; ?></p>
					<h4>What was your negative thought?</h4>
					<p class="mb-0"><?php echo $thought['NegativeThought']; ?></p>
					<h4>What is the evidence for the thought?</h4>
					<p class="mb-0"><?php echo $thought['EvidenceFor']; ?></p>
					<h4>What is the evidence against the thought?</h4>
					<p class="mb-0"><?php echo $thought['EvidenceAgainst']; ?></p>
					<h4>What is an alternative thought?</h4>
					<p class="mb-0"><?php echo $thought['Alternative']; ?></p>
					<h4>How do you feel now?</h4>
					<p class="mb-0"><?php echo $thought['EmotionAfter']; ?></p>
					<a class="btn btn-primary btn-block" href="edit.php?id=<?php echo $id; ?>" role="button">Edit Thought</a>
				<?php }
				} ?>
		</div>
	</div>
</div>

<?php require_once 'footer.php'; ?>