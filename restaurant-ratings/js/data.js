/* Declare map variables globally so they're easily accessed by google maps
Initial coordinates are the Google headquarters in Mountain View FYI */
var map;
var latlng = {
	lat: 37.4219999, 
	lng: -122.0862462
};

/* This grabs the URL and determines the type of page it is from the filename. 
It also grabs the ID passed into the URL, if there is one. Both of these are
passed into the ajax call, and then the map is initialized. */
$(document).ready(function() {
	var url = window.location.href;
	var type = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));
	if(type !== "index" || type.indexOf('.com') == -1) {
		var id = '';
		if(url.indexOf('id') !== -1) {
			id = url.split("=")[1];
		}
		
		if(id !== '') {
			google.maps.event.addDomListener(window, 'load', initMap);
		}
		
		getData(type, id);
	}
});

/* This ajax call sends a request to the server with the type and ID previously
obtained. The web service sends back a boolean code that confirms whether data
was found or not. Then the presence of an ID value determines which function
the data is passed. */
function getData(type, id) {
	$.ajax({
		url : "../ratings/cgi-bin/data.php",
		dataType: 'json',
		type: 'POST',
		data: {
			type: type,
			id: id
		},
		success : function(parsed_json) {
			if( parsed_json['code']  ) {
				if(id == '') {
					createTable( parsed_json['data'], type );
				} else {
					populateData( parsed_json['data'], type );
				}
			} else {
				var error = "<div class='alert alert-danger'>" + parsed_json['data'] + "</div>";
				$('#content').prepend( error );
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			$('#content').prepend("<div class='alert alert-danger'>" + textStatus + ": " + errorThrown + "</div>");
		}
	});
}

/* First the type is changed from plural to singular to prep for creating individual links. 
Then we iterate through the data, building one table row at a time. The HTML is saved to a
single string, which is then all appended to the table. Finally we call the DataTable method
to get all that beautiful sorting and pagination functionality. */
function createTable(data, type) {
	type = type.slice(0, -1);
	$.each(data, function(i, row) {
		var html = "<tr>";
		$.each(Object.keys(row), function(j, col) {
			if(col.indexOf('ID') !== -1) {
				html += "<td><a href='" + type + ".php?id=" + row[col] + "'>" + row[col] + "</a></td>";
			} else {
				html += "<td>" + row[col] + "</td>";
			}
		});
		html += "</tr>";
		$('#data').append(html);
	});
	
	$('#listtable').DataTable();
}

/* This is the most complicated function, so I'll be breaking my comments up. 
Before we start iterating through any data, we declare a few empty variables.
These all will store data from multiple rows. The ratingTypeMap is used to
create the links in the rating table. */
function populateData(data, type) {
	var payments = '';
	var cuisines = '';
	var html = '';
	var hours = '';
	var ratingTypeMap = {
		"customer":"restaurant",
		"restaurant":"customer"
	}
	
	/* We start by iterating through the keys of the entire result set,
		which mostly correspond with different tables. */
	$.each(Object.keys(data), function(i, cat) {
		/* This level is iterating through the rows now, but that is
			not enough for what we need - see next level.
			It is at this point we start populating the variables
			used to construct table rows. */
		$.each(data[cat], function(j, row) {
			if(cat == "rating") {
				html += "<tr>";
			} else if(cat == "hours") {
				hours += "<tr>";
			}
			/* Now we are iterating through the column names of each
				row and using them to access the data.
				Almost all of the column names queried have been used as IDs
				for DOM objects so that they can be easily accessed and updated. */
			$.each(Object.keys(row), function(k, col) {
				var tdID = "#" + col;
				switch(cat) {
					/* We extract the lat/long to create the map and convert
						boolean fields to human-readable "yes/no" values.
						All other fields, we simply find the corresponding
						element and update it. */
					case "main":
						if(col == "latitude") {
							latlng.lat = Number(row[col]);
						} else if(col == "longitude") {
							latlng.lng = Number(row[col]);
						} else if(row[col] == 1) {
							$(tdID).html("Yes");
						} else if(row[col] == 0) {
							$(tdID).html("No");
						} else if(col.indexOf('ID') == -1) {
							$(tdID).html(row[col]);
						}
						break;
					/* Both payments and cuisines are processed the same way:
						by collecting all the values as a comma-separated list. */
					case "payment":
						payments += row[col] + ", ";
						break;
					case "cuisine":
						cuisines += row[col] + ", ";
						break;
					/* Here we are building the rows for the rating table.
						The opposite "type" is needed to construct the 
						appropriate link which is where the ratingTypeMap comes in. */
					case "rating":
						if(col.indexOf('ID') !== -1) {
							html += "<td><a href='" + ratingTypeMap[type] + ".php?id=" + row[col] + "'>" + row[col] + "</a></td>";
						} else {
							html += "<td>" + row[col] + "</td>";
						}
						break;
					/* This just finds the corresponding element and updates it. */
					case "average":
						$(tdID).html(row[col]);
						break;
					/* This is building the rows for the hours table. */
					case "hours":
						hours += "<td>" + row[col] + "</td>";
						break;
				}
			});
			
			/* Here we complete the table row HTML we previously generated. */
			if(cat == "rating") {
				html += "</tr>";
			} else if(cat == "hours") {
				hours += "</tr>";
			}
		});
	});
	
	/* The completed payments and cuisines lists are updated. */
	if(payments !== '') {
		payments = payments.slice(0,-2);
		$('#payments').html(payments);
	}
	
	if(cuisines !== '') {
		cuisines = cuisines.slice(0,-2);
		$('#cuisines').html(cuisines);
	}
	
	/* This creates the DataTable from the rating info. */
	if(html !== "") {
		$('#data').append(html);
		$('#listtable').DataTable();
	}
	
	/* This creates the regular table from the hours info. */
	if(hours !== "") {
		$('#hours').append(hours);
	}
	
	/* Finally we populate the map */
	map.setCenter( new google.maps.LatLng(latlng.lat, latlng.lng) );
	var marker = new google.maps.Marker({
		map: map,
		position: latlng
	});
}

/* This function initializes the map. */
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		center: latlng,
		zoom: 14
	});
	
	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		animation: google.maps.Animation.DROP
	});
}