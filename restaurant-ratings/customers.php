<?php
	$pageTitle = 'Customers';
	require_once 'header.php';
?>

    <!-- Page Content -->
    <div class="container">

      <h1 class="my-4"><?php echo $pageTitle; ?></h1>

      <hr>
	  
	  <div id="content">
		  <table class="table table-striped table-hover table-bordered" id="listtable">
			  <thead class="thead-dark">
				<tr>
				  <th>ID</th>
				  <th>Birth Year</th>
				  <th>Drinking Level</th>
				  <th>Employment Status</th>
				  <th>Personality</th>
				</tr>
			  </thead>
			  <tbody id="data">
			  </tbody>
		  </table>
	  </div>

    </div>
    <!-- /.container -->
	
<?php require_once 'footer.php' ?>