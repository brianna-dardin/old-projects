$().ready(function() {
	$("form").validate({
		rules: {
			name: {
				required: true,
				minlength: 2
			},
			current: {
				required: true,
				minlength: 5
			},
			newpass: {
				required: true,
				minlength: 5
			},
			confirm: {
				required: true,
				minlength: 5,
				equalTo: "#newpass"
			},
			email: {
				required: true,
				email: true
			},
			country: "required",
			zone: "required"
		},
		messages: {
			name: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			current: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			newpass: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirm: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address",
			country: "Please choose a country.",
			zone: "Please choose a timezone."
		}
	});
});