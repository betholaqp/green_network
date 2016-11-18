$(document).on("ready",function(){

	$('#contenedor_btn_juego').on('click','#resp', function(){
		var respuesta = $(this);
		if (respuesta.attr("data-resp")==1) 
		{
			swal({
			  type: "success",
			  title: "Respuesta Correcta!",
			  text: "",
			  timer: 1500,
			  showConfirmButton: false
			});

		}
		else
		{
			swal({
			  type: "error",
			  title: "Respuesta Equivocada!",
			  text: "",
			  timer: 1500,
			  showConfirmButton: false
			});	
		}

	});

	$("#nueva_pregunta").click(function(){
		$("#contenedor_btn_juego").empty();
		ramdon_botones();
	});

	ramdon_botones();

	function ramdon_botones()
	{
		var boton = ["res1","res2","res3","res4"];

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



});