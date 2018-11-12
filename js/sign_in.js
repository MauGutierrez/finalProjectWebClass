$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});


$('.button-login').click(function(event){
	event.preventDefault();
	if ($('#Matriculation-Number').val() == "" || $('#password').val() == ""){
		var messageLog = '';
		if ($('#Matriculation-Number').val() == ""){
			messageLog += '- Matriculation Number \n';
		}
		if ($('#password').val() == ""){
			messageLog += '- Password';
		}
		alert('Next fields must be filled out: \n' + messageLog);

		return false;
	}
	else{
		let remember = 'false';
		if ($('.rememberMe').prop('checked')){
			remember = 'true';
		}

		let jsonToSend = {
			"user"		: $('#Matriculation-Number').val(),
			"pass"		: $('#password').val(),
			"cookie"	: remember,
			"action"	: "LOGIN"
		};

		$.ajax({
			url			: "./data/applicationLayer.php",
			type		: "GET",
			data		: jsonToSend,
			ContentType : "application/json",
			dataType	: "json",
			success		: function(data){
				$(location).attr("href", "homepage.html");
			},
			error		: function(error){
				console.log(error);
				alert('User and/or Password are incorrect. Please try again.');
			}
		});
	}
});


$.ajax({
	url : './data/cookieService.php',
	type : 'GET',
	dataType : 'json',
	success : function(data){
		$("#username").val(data.username);
	},
	error : function(errorMsg){
		console.log(errorMsg);
	}
});


$('.button-register').click(function(){
	event.preventDefault();
	if ($('#First-Name').val() == "" || $('#Last-Name').val() == "" || $('#id').val() == "" ||
		$('#Car-Model').val() == "" || $('#Car-Number').val() == "" || $('#email').val() == "" ||
		$('#Password').val() == "" || $('#Parking-slot').val() == ""){
		
		var messageRegister = '';

		if ($('#First-Name').val() == ""){
			messageRegister += '- First Name \n';
		}
		if ($('#Last-Name').val() == ""){
			messageRegister += '- Last Name \n';
		}
		if ($('#id').val() == ""){
			messageRegister += '- Matriculation Number \n';
		}
		if ($('#Parking-slot').val() == ""){
			messageRegister += '- Parking Slot \n';
		}
		if ($('#Car-Model').val() == ""){
			messageRegister += '- Car Model \n';
		}
		if ($('#Car-Number').val() == ""){
			messageRegister += '- Car plate number \n';
		}
		if ($('#email').val() == ""){
			messageRegister += '- Email \n';
		}
		if ($('#Password').val() == ""){
			messageRegister += '- Password \n';
		}

		alert('Next fields must be filled out: \n' + messageRegister);
		return false;
	}
	if ($("#Password").val() != $("#Password-Confirmation").val()){
		alert('Password and Password confirmation must be the same');
		return false;
	}
	else {
		let jsonToSend = {
			"FirstName" : $('#First-Name').val(),
			"LastName" 	: $('#Last-Name').val(),
			"id"		: $('#id').val(),
			"parking"	: $('#Parking-slot').val(),
			"carModel" 	: $('#Car-Model').val(),
			"carID"		: $('#Car-Number').val(),
			"email" 	: $('#email').val(),
			"Password"	: $('#Password').val(),
			"action"	: "REGISTER"
		};

		$.ajax({
			url			: "data/applicationLayer.php",
			type		: "POST",
			data		: jsonToSend,
			ContentType : "application/json",
			dataType	: "json",
			success		: function(data){
				$(location).attr("href", "homepage.html");
			},
			error		: function(error){
				console.log(error);
				alert('Username already in use. Try another.');
			} 
		});
	}
});

$('.button-clear').click(function(){
	$('.register-form')[0].reset();
	return false;
});