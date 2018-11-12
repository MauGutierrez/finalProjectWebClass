$(document).ready(function(){
	var user = $('#myProfile').text();
	let jsonToSend = {
		"user"	: user,
		"action" : "ADD_PARKING_CHANGE"
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
								<td><center> ${data[i].parking} </center></td>
								<td><center> ${data[i].Owner} </center></td>
								<td><center><button id=${data[i].Owner} value=${data[i].parking} type="submit" class="btn btn-default">Request change</button></center></td>
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
					"action"	: "REQUEST_CHANGE"
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