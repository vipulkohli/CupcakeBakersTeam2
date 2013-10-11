//input validation for out account creation and login form

// we wait for the DOM to load
$(document).ready(function () {
	
	//login form
	//must be an email and password that is 8 characters in length
	$('#loginForm').validate({ 
        rules: {
        	email: {
                required: true,
                email: true
            },
            password: {
            	required: true,
            	minlength: 8,
            }
        },
        submitHandler: function(form) {
            form.submit();
		  },
		  errorPlacement: function(error, element) {
		  	error.insertBefore(element);
		  }
    });

	//Account creation form where all elements are required
	//password is set to 8 characters
	//ZIP set to 5 digits
	//email must be an email
	//Telephone must be a legal US phone #
	$('#registerForm').validate({ 
        rules: {
        	join_mailing_list: {
        		required: true
        	},
        	first_name: {
        		required: true
        	},
        	last_name: {
        		required: true
        	},
            email: {
                required: true,
                email: true
            },
            password: {
            	required: true,
            	minlength: 8,
            },
            telephone: {
            	required: true,
            	phoneUS: true
            },
            address: {
            	required: true
            },
            city: {
            	required: true,
            },
            state: {
            	required: true,
            },
            zip_code: {
            	required: true,
            	digits: true,
            	minlength: 5,
            }
        },
        submitHandler: function(form) {
		    form.submit();
		  },
			// the output errors are inserted before the feild, notifying the user
		  errorPlacement: function(error, element) {
		  	error.insertBefore(element);
		  }
    });
});
