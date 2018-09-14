<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // if session is not set this will redirect to login page
 $id = $_SESSION['user'];
 
 if( !isset($id) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $user=$db->query("SELECT * FROM user WHERE ID=$id");
 $userRow=$user->fetch_assoc();
 
 $title = 'Welcome ' . $userRow['Username'];
 require_once 'header.php';
?>

 <div class="container" id="wrapper">
    <div class="row">
		<div class="col-lg-12">
		<?php
			$thoughts=$db->query("SELECT * FROM thought WHERE UserID=$id ORDER BY DateUpdated DESC");
			if($thoughts->num_rows > 0) { ?>
		<table class="table table-striped">
		  <thead>
			<tr>
			  <th>Date</th>
			  <th>Emotion</th>
			  <th>Thought</th>
			  <th class="hidden-xs">Alternative</th>
			</tr>
		  </thead>
		  <tbody>
			<?php
				 while ($thought = $thoughts->fetch_assoc()) { ?>
					<tr>
					  <th scope="row"><a href='view.php?id=<?php echo $thought['ID']?>'>
					  <?php 
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
						}?></a></th>
					  <td><?php echo $thought['EmotionBefore'] ?></td>
					  <td><?php echo $thought['NegativeThought'] ?></td>
					  <td class="hidden-xs"><?php echo $thought['Alternative'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
        <div class="jumbotron">
			<h1 class="display-3">No thoughts found.</h1>
			<p class="lead">Why not try adding a new thought?</p>
			<p class="lead">
				<a class="btn btn-primary btn-lg" href="new.php" role="button">Add New Thought</a>
			</p>
		</div>
		<?php } ?>
        </div>
    </div>
    </div>
    
<?php require_once 'footer.php'; ?>