<?php

// the API file
require_once 'api.php';

// creates a new instance of the api class
$api = new api();

// message to return
$message = array();

if( isset($_POST['type']) ) {
	/* Each of the different types of pages in this website all require different data,
		so this switch statement defines the queries needed for each type and sends them
		to the appropriate query processing function. */
	switch($_POST['type']) {
		case 'restaurants':
			$query = 'SELECT restaurant.placeID, restaurant.name, AVG(rating.rating) as AvgRating, AVG(rating.food_rating) AS FoodRating, AVG(rating.service_rating) AS ServiceRating FROM RESTAURANT JOIN RATING ON restaurant.placeID = rating.placeID GROUP BY restaurant.placeID, restaurant.name';
			$message = $api->sql_query($query);
			break;
		case 'customers':
			$query = 'SELECT userID, birth_year, drink_level, activity, personality FROM CUSTOMER';
			$message = $api->sql_query($query);
			break;
		case 'restaurant':
			if( isset($_POST['id']) ) {
				$id = $_POST['id'];
				$query = 'SELECT * FROM RESTAURANT WHERE placeID = ' . $id . ';';
				$query .= 'SELECT payment.payment FROM RESTAURANT_PAYMENT JOIN PAYMENT ON restaurant_payment.paymentID = payment.paymentID WHERE restaurant_payment.placeID = ' .  $id . ';';
				$query .= 'SELECT cuisine.cuisine FROM RESTAURANT_CUISINE JOIN CUISINE ON restaurant_cuisine.cuisineID = cuisine.cuisineID WHERE restaurant_cuisine.placeID = ' .  $id . ';';
				$query .= 'SELECT userID, food_rating, service_rating, rating FROM RATING WHERE rating.placeID = ' .  $id . ';';
				$query .= 'SELECT AVG(rating.rating) as AvgRating, AVG(rating.food_rating) AS FoodRating, AVG(rating.service_rating) AS ServiceRating FROM  RATING WHERE rating.placeID = ' . $id . ' GROUP BY rating.placeID;';
				$query .= 'SELECT days, hours FROM RESTAURANT_HOURS WHERE placeID = ' . $id;
				
				$keys = ['main','payment','cuisine','rating', 'average', 'hours'];
				
				$message = $api->multi_query($query, $keys);
			}
			break;
		case 'customer':
			if( isset($_POST['id']) ) {
				$id = $_POST['id'];
				$query = 'SELECT * FROM CUSTOMER WHERE userID = ' .  $id . ';';
				$query .= 'SELECT payment.payment FROM CUSTOMER_PAYMENT JOIN PAYMENT ON customer_payment.paymentID = payment.paymentID WHERE customer_payment.userID = ' .  $id . ';';
				$query .= 'SELECT cuisine.cuisine FROM CUSTOMER_CUISINE JOIN CUISINE ON customer_cuisine.cuisineID = cuisine.cuisineID WHERE customer_cuisine.userID = ' .  $id . ' ORDER BY cuisine.cuisine ASC;';
				$query .= 'SELECT placeID, food_rating, service_rating, rating FROM RATING WHERE rating.userID = ' .  $id . ';';
				$query .= 'SELECT AVG(rating.rating) as AvgRating, AVG(rating.food_rating) AS FoodRating, AVG(rating.service_rating) AS ServiceRating FROM  RATING WHERE rating.userID = ' . $id . ' GROUP BY rating.userID';
				
				$keys = ['main','payment','cuisine','rating', 'average'];
				
				$message = $api->multi_query($query, $keys);
			}
			break;
	}
} 

if(is_null($message['code'])) {
	$message['code'] = 0;
	$message['data'] = "We're sorry but something went wrong. Please try again.";
}

//the JSON message
header('Content-type: application/json; charset=utf-8');
echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);