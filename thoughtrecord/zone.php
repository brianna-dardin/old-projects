<?php
include_once 'dbconnect.php';

if(isset($_POST['country'])) {
	$country = $_POST['country'];
	$query = "SELECT zone_id, zone_name FROM zone WHERE country_code='".$country."' ORDER BY zone_name ASC";

	$sth = $db->query($query);
	$result = $sth->fetch_all();

	echo json_encode($result);
	exit();
}