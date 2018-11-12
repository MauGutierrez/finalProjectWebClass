let jsonToSend = {
	"action"	: 	"GET_USER"
};

$.ajax({
	url 		: "data/applicationLayer.php",
	type 		: "GET",
	data 		: jsonToSend,
	dataType  	: "json",
	success		: function(data){
		$("#myProfile").text(`${data.username}`);
	},
	error		: function(error){
		$(location).attr('href', 'parkingTec.html');
	}
})