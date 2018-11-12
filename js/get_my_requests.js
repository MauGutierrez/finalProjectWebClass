$(document).ready(function(){
	var user = $('#myProfile').text();
	let jsonToSend = {
		"user"	: user,
		"action" : "ACCEPT_REQUEST"
	};

	$.ajax({
		url			: "data/applicationLayer.php",
		type		: "GET",
		data 		: jsonToSend,
		ContentType : "application/json",
		dataType	: "json",
		success: function(data){
			var table = $('#table-parking-lot').html();
			var i = 0;
			$(data).each(function(){
				table += `<tr>
							<th scope="row"><center> ${i+1} </center></th>
								<td><center> ${data[i].friend} </center></td>
								<td><center> ${data[i].space} </center></td>
								<td><center><button id=${data[i].friend} value=${data[i].space} type="submit" class="btn btn-default">Accept change</button></center></td>
	                	  </tr>`;

				i++;
			});
			$('#table-parking-lot').append(table);
			$('button').click(function(){
				var userFriend = (this.id);
				var space = (this.value);
				var user = $("#myProfile").text();

				let jsonToSend = {
					"username"	: user,	
					"friend"	: userFriend,
					"space"		: space,
					"action"	: "SEND_ACCEPT"
				};
				
				$.ajax({
					url			: 	"data/applicationLayer.php",
					type		: 	"POST",
					data 		: 	jsonToSend,
					ContentType	: 	"application/json",
					dataType	: 	"json",
					success		: 	function(data){
						alert('Operation succesfull. You have a new space!');
					},
					error : function(error){
						console.log(error);
					}
				})
			})
		},
		error : function(error){
			console.log(error);
		}
	});



})