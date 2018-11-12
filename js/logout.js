$(document).ready(function(){
	let jsonToSend = {
		"action"	: "FINISH_SESSION"
	};

	$('#Logout').click(function(){
		$.ajax({
			url 		: "data/applicationLayer.php",
			type 		: "DELETE",
			data 		: jsonToSend,
			dataType  	: "json",
			success		: function(data){
				$(location).attr('href', 'parkingTec.html');
			}
		})
	});
});