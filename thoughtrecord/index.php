<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // it will never let you open index page if session is set
 if ( isset($_SESSION['user'])!="" ) {
  header("Location: home.php");
  exit;
 }
 ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Thought Record - Keep track of your thoughts for free!</title>
<link rel="stylesheet" href="bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="styles.css" type="text/css"  />
</head>
<body>

<div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>Thought Record</h1>
                        <h3>Keep track of your thoughts for free!</h3>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->

<div class="content-section-a">

        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <h2 class="section-heading">About Thought Record</h2>
                    <p class="lead">Here you can keep track of your negative thoughts and learn how to turn them into positive ones!</p>
					<p class="lead">All you have to do is answer a few questions about your thought and you will be challenged to turn it around!</p>
					<p class="lead">This service is 100% free - so feel free to sign up today and begin your journey towards positivity!</p>
                </div>
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                    <img class="img-responsive" src="screenshot.png" alt="">
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.content-section-a -->

<div class="banner">

        <div class="container">

            <div class="row">
                <div class="col-lg-6">
                    <h2>Get Started:</h2>
                </div>
                <div class="col-lg-6">
                    <ul class="list-inline banner-social-buttons">
                        <li>
                            <a href="register.php" class="btn btn-default btn-lg"><span class="network-name">Register</span></a>
                        </li>
                        <li>
                            <a href="signin.php" class="btn btn-default btn-lg"><span class="network-name">Sign In</span></a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.banner -->

<!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="copyright text-muted small">Copyright &copy; <a href="http://briannadardin.com">Brianna Dardin</a> 2016. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

<?php require_once 'footer.php'; ?>