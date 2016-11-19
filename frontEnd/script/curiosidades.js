$(document).ready(function(){
	
	console.log(localStorage.getItem("token"));
	console.log(localStorage.getItem("base_url"));

	mostar_imagen();

	function mostar_imagen()
	{
		$.ajax({
			type:'GET',
			url: localStorage.getItem("base_url")+"random"
			
		}).success(function(data){
			
			if (data.status == "success") 
			{
				console.log(data.content.url);
				$("#imagen").attr('src',localStorage.getItem("base_url")+data.content.url);
			}
			
		}).error(function(data){

			console.log(data);

		});	
	}

	setTimeout(function(){ window.location.href = "index.html"; }, 5000);


});