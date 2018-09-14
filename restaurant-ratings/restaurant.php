<?php
	if( isset( $_GET['id'] ) ) {
		$pageTitle = 'Restaurant #' . $_GET['id'];
	}
	require_once 'header.php';
?>

    <!-- Page Content -->
    <div class="container">

      <h1 class="my-4" id="name"></h1>

      <hr>
	  
	  <div id="content">
		<div class="row">
			<div class="col-md-6">
				<h3>Basic Info</h3>
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody>
						<tr>
						  <td class="fieldnames">Area:</td>
						  <td id="area"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Price:</td>
						  <td id="price"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Parking:</td>
						  <td id="parking_lot"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Accessibility:</td>
						  <td id="accessibility"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Franchise:</td>
						  <td id="franchise"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Payment Options:</td>
						  <td id="payments"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<h3>Style</h3>
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody>
						<tr>
						  <td class="fieldnames">Smoking Allowed:</td>
						  <td id="smoking_area"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Alcohol Served:</td>
						  <td id="alcohol"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Ambience:</td>
						  <td id="Rambience"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Dress Code:</td>
						  <td id="dress_code"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Other Services:</td>
						  <td id="other_services"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Cuisines:</td>
						  <td id="cuisines"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h3>Hours</h3>
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody id="hours">
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3>Location</h3>
				<div id="map" style="height: 200px;">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3>Average Ratings Received</h3>
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody>
						<tr>
						  <td class="fieldnames">Food Rating:</td>
						  <td id="FoodRating"></td>
						  <td class="fieldnames">Service Rating:</td>
						  <td id="ServiceRating"></td>
						  <td class="fieldnames">Overall Rating:</td>
						  <td id="AvgRating"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3>Ratings</h3>
				<table class="table table-striped table-hover table-bordered" id="listtable">
				  <thead class="thead-dark">
					<tr>
					  <th>Customer</th>
					  <th>Food Rating</th>
					  <th>Service Rating</th>
					  <th>Overall Rating</th>
					</tr>
				  </thead>
				  <tbody id="data">
				  </tbody>
			  </table>
			</div>
		</div>
	  </div>
	</div>
    <!-- /.container -->
	
<?php require_once 'footer.php' ?>