<?php
	$pageTitle = 'Welcome to Restaurant Ratings';
	require_once 'header.php';
?>

 <header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="carousel-item active" style="background-image: url('img/breakfast-min.jpg')">
            <div class="carousel-caption d-none d-md-block">
              <h2>Breakfast</h2>
            </div>
          </div>
          <div class="carousel-item" style="background-image: url('img/sweet-potato-fries-min.jpg')">
            <div class="carousel-caption d-none d-md-block">
              <h2>Appetizer</h2>
            </div>
          </div>
          <div class="carousel-item" style="background-image: url('img/pizza-min.jpg')">
            <div class="carousel-caption d-none d-md-block">
              <h2>Dinner</h2>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>

    <!-- Page Content -->
    <div class="container">

      <h1 class="my-4"><?php echo $pageTitle; ?></h1>

      <!-- Marketing Icons Section -->
      <div class="row mx-auto">
        <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h4 class="card-header">Restaurants</h4>
            <div class="card-body">
              <p class="card-text">Learn more about various restaurants in Mexico and the ratings they received.</p>
            </div>
            <div class="card-footer">
              <a href="restaurants.php" class="btn btn-primary">Learn More</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h4 class="card-header">Customers</h4>
            <div class="card-body">
              <p class="card-text">Learn more about the customers rating the restaurants and the ratings they gave.</p>
            </div>
            <div class="card-footer">
              <a href="customers.php" class="btn btn-primary">Learn More</a>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <hr>

    </div>
    <!-- /.container -->

<?php require_once 'footer.php' ?>