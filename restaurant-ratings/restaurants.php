<?php
	$pageTitle = 'Restaurants';
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
				  <th>Name</th>
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
    <!-- /.container -->
	
<?php require_once 'footer.php' ?>