$(document).ready(function(){
	var user = $('#myProfile').text();
	let jsonToSend = {
		"username" 	: user,
		"action"	: "GET_USER_INFO"
	};
	$.ajax({
		url			: "data/applicationLayer.php",
		type		: "POST",
		data		: jsonToSend,
		ContentType : "application/json",
		dataType	: "json",
		success: function(data){
			let newHtml = "";
			newHtml += `${data.firstName} ${data.lastName}`;
			$(".panel-title").append(newHtml);
			newHtml = "";
			newHtml += `<tbody>
						<tr>
							<td> Matriculation Number:</td> 
							<td> ${user} </td>
						</tr>
						<tr>
							<td> Email:</td> 
							<td> ${data.email} </td>
						</tr>
						<tr>
							<td> Parking Space:</td> 
							<td> ${data.parking} &nbsp; &nbsp; &nbsp; <button type="submit" class="btn btn-default">Publish your space</button></td>
						</tr>
						<tr>
							<td> Car Model:</td> 
							<td> ${data.carModel} </td>
						</tr>
						<tr>
							<td> Car plate number:</td> 
							<td> ${data.carID} </td>
						</tr>
						</tbody>`;
			$(".table").append(newHtml);	
			$('button').click(function(){
				let jsonToSend = {
					"username"	: user,
					"parkingSpace" : data.parking,
					"action"	: "POST_PLACE"
				};
				
				$.ajax({
					url			: 	"data/applicationLayer.php",
					type		: 	"POST",
					data 		: 	jsonToSend,
					ContentType	: 	"application/json",
					dataType	: 	"json",
					success		: 	function(data){
						alert('Your place now is publish!');
					}
				})
			})
		},
		error	: function(error){
			console.log(error);
		}
	});
});