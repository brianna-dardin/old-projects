<?php
ob_start();
session_start();
require_once 'dbconnect.php';

if( !isset($_SESSION['user']) ) {
  header("Location: index.php");
  exit;
 }

 if ( isset($_POST['btn-submit']) ) {
	// clean user inputs to prevent sql injections
	$context = clean($_POST['context']); 
	$negThought = clean($_POST['negThought']); 
	$evidenceFor = clean($_POST['evidenceFor']); 
	$evidenceAgainst = clean($_POST['evidenceAgainst']); 
	$alternative = clean($_POST['alternative']); 
	
	$id = $_SESSION['user'];
	$emotionBefore = $_POST['emotionBefore'];
	$emotionAfter = $_POST['emotionAfter'];
	 
	$query = "INSERT INTO thought(Context,NegativeThought,EvidenceFor,EvidenceAgainst,Alternative,UserID,EmotionBefore,EmotionAfter) VALUES('$context','$negThought','$evidenceFor','$evidenceAgainst','$alternative','$id','$emotionBefore','$emotionAfter')";
	$res = $db->query($query);

	if ($res) {
	$errTyp = "success";
	$errMSG = "Posted successfully.";
	unset($name);
	unset($context); 
	unset($negThought); 
	unset($evidenceFor); 
	unset($evidenceAgainst); 
	unset($alternative); 
	unset($id);
	unset($emotionBefore);
	unset($emotionAfter);
	} else {
	$errTyp = "danger";
	$errMSG = "Something went wrong, try again later..."; 
	} 
 }
 
 if ( isset($_POST['btn-cancel']) ) {
	header('Location: home.php');
  }

$title = 'New Thought';
require_once 'header.php'; ?>

<div class="container" id="wrapper">
    <div class="row">
        <div class="col-lg-12">

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
    <textarea class="form-control" name="context" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="emotionBefore">How did you feel?</label>
    <select class="form-control" name="emotionBefore">
      <option value="Angry">Angry</option>
      <option value="Anxious">Anxious</option>
      <option value="Depressed">Depressed</option>
    </select>
  </div>
  <div class="form-group">
    <label for="negThought">What was your negative thought?</label>
    <textarea class="form-control" name="negThought" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="evidenceFor">What is the evidence for the thought?</label>
    <textarea class="form-control" name="evidenceFor" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="evidenceAgainst">What is the evidence against the thought?</label>
    <textarea class="form-control" name="evidenceAgainst" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="alternative">What is an alternative thought?</label>
    <textarea class="form-control" name="alternative" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="emotionAfter">How do you feel now?</label>
    <select class="form-control" name="emotionAfter">
      <option value="Calm">Calm</option>
	  <option value="Happy">Happy</option>
	  <option value="Angry">Angry</option>
      <option value="Anxious">Anxious</option>
      <option value="Depressed">Depressed</option>
    </select>
  </div>
  <div class="form-group">
	 <button type="submit" class="btn btn-block btn-primary" name="btn-submit">Submit</button>
	</div>
  <div class="form-group">
	 <button type="submit" class="btn btn-block btn-primary" name="btn-cancel">Cancel</button>
	</div>
</form>

</div> <!-- col-lg-12 -->
</div> <!-- row -->
</div> <!-- container -->

<?php require_once 'footer.php'; ?>