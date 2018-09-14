$(document).ready(function() {
	var country = $("#country").val();
	if(country != null) {
		loadZone(country);
	}
});

$('#country').on('change', function() {
    loadZone( $(this).val() )
});

function loadZone(country){
	$("#zone").children().remove();
	$.ajax({
		type: "POST",
		url: "zone.php",
		data: "country=" + country
		}).done(function( result ) {
			data = JSON.parse(result);
			var userzone = $('#zone').data('userzone');
			$(data).each(function(){
				if(this[0] == userzone) {
					$("#zone").append($('<option>', {
						value: this[0],
						text: this[1],
						selected: true,
					}));
				} else {
					$("#zone").append($('<option>', {
						value: this[0],
						text: this[1],
					}));
				}
			})
		});
}