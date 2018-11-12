$(document).ready(function(){
	var user = $('#myProfile').text();
	let jsonToSend = {
		"user"	: user,
		"action" : "ADD_PARKING_SPOTS"
	};

	$.ajax({
		url			: "data/applicationLayer.php",
		type		: "GET",
		data 		: jsonToSend,
		ContentType : "application/json",
		dataType	: "json",
		success: function(data){
			console.log(data);
			var table = $('#table-parking-lot').html();
			var i = 0;
			$(data).each(function(){
				table += `<tr>
							<th scope="row"><center> ${i+1} </center></th>
								<td><center> ${data[i].ID} </center></td>
								<td><center> ${data[i].parking} </center></td>
								<td><center><button id=${data[i].ID} value=d=${data[i].parking} type="submit" class="btn btn-default">Request place</button></center></td>
	                	  </tr>`;

				i++;
			});
			$('#table-parking-lot').append(table);
			$('button').click(function(){
				var ID = (this.id);
				var space = (this.value);
				var user = $("#myProfile").text();

				let jsonToSend = {
					"username"	: user,	
					"ID"		: ID,
					"space"		: space,
					"action"	: "REQUEST_PLACE"
				};

				$.ajax({
					url			: 	"data/applicationLayer.php",
					type		: 	"POST",
					data 		: 	jsonToSend,
					ContentType	: 	"application/json",
					dataType	: 	"json",
					success		: 	function(data){
						alert('Your request has been done!');
					}
				})
			})
		},
		error : function(error){
			console.log(error);
		}
	});



})