<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>REST Account and Contact List</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<style>
    #accounts {
        background-color: #797f86;
    }
    
    h1 {
        text-align: center;
        text-shadow: none;
        color: white;
    }
    
    .ui-li-static {
        white-space: normal !important;
    }
    
    .bold {
        font-weight: bold;
    }
</style>
</head>

<body>

<section id="accounts" data-role="page">
    <div data-role="content">
		<h1>Simple REST Accounts & Contacts</h1>
		<div data-role='collapsible-set'>
		<?php
		require_once 'request.php';
		
		foreach ($acct_array as $acct) {
			$html = "<div data-role='collapsible'>";
			$html .= "<h3>" . $acct['Name'] . "</h3>";
			
			if($acct['Industry'] != null) {
				$html .= "<p class='bold'>" . $acct['Industry'] . "</p>";
			}
			
			if($acct['Description'] != null) {
				$html .= "<p>" . $acct['Description'] . "</p>";
			}
			
			if($acct['Contacts'] != null) {
				$html .= "<ul data-role='listview' data-inset='true'>";
				
				foreach($acct['Contacts']['records'] as $cont) {
					$html .= "<li>" . $cont['Name'];
					
					if($cont['Phone'] != null) {
						$html .= " | " . $cont['Phone'];
					}
					
					if($cont['Email'] != null) {
						$html .= " | " . $cont['Email'];
					}
					
					$html .= "</li>";
				}	
				
				$html .= "</ul>";
			}
			
			echo $html . "</div>";
		} ?>
		</div>
    </div>
</section>
    
</body>

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

</html>