$(document).ready(function () {
	

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
		  errorPlacement: function(error, element) {
		  	error.insertBefore(element);
		  }
    });
});