<?php
	if( isset( $_GET['id'] ) ) {
		$pageTitle = 'Customer #' . $_GET['id'];
	}
	require_once 'header.php';
?>

    <!-- Page Content -->
    <div class="container">

      <h1 class="my-4"><?php echo $pageTitle; ?></h1>

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
						  <td class="fieldnames">Birth Year:</td>
						  <td id="birth_year"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Height:</td>
						  <td id="height"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Weight:</td>
						  <td id="weight"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Marital Status:</td>
						  <td id="marital_status"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Dependent Status:</td>
						  <td id="hijos"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Employment Status:</td>
						  <td id="activity"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Transport:</td>
						  <td id="transport"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Budget:</td>
						  <td id="budget"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<h3>Personality</h3>
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody>
						<tr>
						  <td class="fieldnames">Smoker:</td>
						  <td id="smoker"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Drinking Level:</td>
						  <td id="drink_level"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Ambience:</td>
						  <td id="ambience"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Dress Code:</td>
						  <td id="dress_preference"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Personality:</td>
						  <td id="personality"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Interest:</td>
						  <td id="interest"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Religion:</td>
						  <td id="religion"></td>
						</tr>
						<tr>
						  <td class="fieldnames">Favorite Color:</td>
						  <td id="color"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-hover table-bordered">
					<thead>
					</thead>
					<tbody>
						<tr>
						  <td class="fieldnames">Payment Options:</td>
						  <td id="payments"></td>
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
			<div class="col-md-12">
				<h3>Location</h3>
				<div id="map" style="height: 200px;">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3>Average Ratings Given</h3>
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
					  <th>Restaurant</th>
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