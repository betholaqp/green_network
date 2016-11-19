$(document).on("ready",function(){

	//console.log(localStorage.getItem("base_url"));
	var id_pregunta;
	portafolio();
	peticion_pregunta();

	$('#contenedor_btn_juego').on('click','#resp', function(){
		var respuesta = $(this);
		if (respuesta.attr("data-resp")==1) 
		{
			$.ajax({
				data:{"token":localStorage.getItem("token"),"id":id_pregunta},
				type:'POST',
				url: localStorage.getItem("base_url")+"addpoint",
				dataType:"JSON"
			}).success(function(data){


				swal({
				  type: "success",
				  title: "Respuesta Correcta!",
				  text: data.content,
				  timer: 1500,
				  showConfirmButton: false
				});

				peticion_pregunta();
				getPoint();
			}).error(function(data){

				console.log(data);

			});			

		}
		else
		{

			$.ajax({
				data:{"token":localStorage.getItem("token"),"id":id_pregunta},
				type:'POST',
				url: localStorage.getItem("base_url")+"nopoint",
				dataType:"JSON"
			}).success(function(data){
				
				swal({
				  type: "error",
				  title: "Respuesta Equivocada!",
				  text: "",
				  timer: 1500,
				  showConfirmButton: false
				});	

				peticion_pregunta();
				getPoint();
				
			}).error(function(data){

				console.log(data);

			});			
			
		}

	});

	$("#nueva_pregunta").click(function(){
		$("#contenedor_btn_juego").empty();
		ramdon_botones();
	});



	function portafolio()
	{
		$.ajax({
			type:'POST',
			url: localStorage.getItem("base_url")+"profile",
			dataType:"JSON",
			data:{"token":localStorage.getItem("token")}
		}).success(function(data){
			
			$("#username").text(data.username);
			$("#jugador").text(data.nombres+" "+data.apellidos);
			$("#correo").text(data.correo);
			$("#fecha").text(data.fnac);
			
		}).error(function(data){

			console.log(data);

		});

	}

	function peticion_pregunta()
	{
		//console.log(localStorage.getItem("token"));

		$.ajax({
			type:'POST',
			url: localStorage.getItem("base_url")+"randomquestion",
			dataType:"JSON",
			data:{"token":localStorage.getItem("token")}
			
		}).success(function(data){
			$("#preg").text(data.content.preg);
			ramdon_botones(data.content);	
			id_pregunta = data.content.id;		
		}).error(function(data){

			console.log(data);
		});

	}

	function ramdon_botones(datos)
	{
		$("#contenedor_btn_juego").empty();
		var boton = [datos.preg,datos.inc1,datos.inc2,datos.inc3];
		var prueba = [0,1,2,3];

		for (var i = prueba.length-1; i >=0; i--) {
 
		    var randomIndex = Math.floor(Math.random()*(i+1)); 
		    var itemAtIndex = prueba[randomIndex]; 
		     
		    prueba[randomIndex] = prueba[i]; 
		    prueba[i] = itemAtIndex;
		}

		for (var i = 0; i < prueba.length; i++) {
			if (prueba[i] == 0) 
				$("#contenedor_btn_juego").append('<a class="btn" data-resp="1" id="resp">'+boton[prueba[i]]+'</a><br><br>');
			else
				$("#contenedor_btn_juego").append('<a class="btn" data-resp="0" id="resp">'+boton[prueba[i]]+'</a><br><br>');
		}

	}



	//Sacar Semillas Totales

	getPoint();

	function getPoint()
	{
		$.ajax({
			type:'POST',
			url: localStorage.getItem("base_url")+"getpoints",
			dataType:"JSON",
			data:{"token":localStorage.getItem("token")}
		}).success(function(data){
			
			$("#semillas").html(data.content);
			
		}).error(function(data){
			console.log(data);
		});		
	}

	//Curiosidad Aleatoria

	mostar_imagen();

	function mostar_imagen()
	{
		$.ajax({
			type:'GET',
			url: localStorage.getItem("base_url")+"random"
			
		}).success(function(data){
			
			if (data.status == "success") 
			{
				//console.log(data.content.url);
				$("#curiosidad").attr('src',localStorage.getItem("base_url")+data.content.url);
			}
			
		}).error(function(data){

			console.log(data);

		});	
	}

	//Cerrar Session

	$("#logaut").click(function(){
		localStorage.removeItem("token");
		localStorage.removeItem("base_url");
		window.location.href = "login.html";
	});

});