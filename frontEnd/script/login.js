$(document).on("ready",function(){

	//var host = "http://127.0.0.1/hackaton2016/green_network/backEnd/apimmt/";
	var host = "http://192.168.43.132/apimmt/";
	$("#nuevo_usuario").hide();

	$("#enviar").click(function(){
		var username = $("[name = username]");
		var password = $("[name = password]");

		var data = {
			username: username.val(),
			password: password.val()
		};

		$.ajax({
			type:'POST',
			url: host+"login",
			dataType:"JSON",
			data:data	
		}).success(function(data){
			
			if (data.status == "success") 
			{
				localStorage.setItem("token",data.content.token);
				localStorage.setItem("base_url",host);
				window.location.href = "curiosidades.html";
			}
			else
			{
				$("#alerta").text("Revice los Datos Ingresados");
				localStorage.removeItem("token");
				localStorage.removeItem("base_url");
			}
			
		}).error(function(data){

			console.log(data);

		});
	});

	$("#btn_nuevo").click(function(){
		$("#logearse").fadeOut(200);
		$("#nuevo_usuario").fadeIn(200);
	});

	$("#btn_atras").click(function(){
		$("#nuevo_usuario").fadeOut(200);
		$("#logearse").fadeIn(200);		
	});	

	$("#btn_crear_usuario").click(function(){

		var usuario = $("[name = new_usuario]");
		var password = $("[name = new_password]");
		var repetir_password = $("[name = new_password_repetir]");
		var nombre = $("[name = new_nombre]");
		var apellido = $("[name = new_apellido]");
		var correo = $("[name = new_correo]");
		var fecha = $("[name = new_fecha_nac]");

		if (usuario.val() != "" && password.val() != "" && repetir_password.val() != "" && nombre.val() != "" && correo.val() != "" && fecha.val() != "" && apellido.val() != "") {
			if (password.val() == repetir_password.val()){
					
				var data = {
					username: usuario.val(),
					password: password.val(),
					nombres: nombre.val(),
					apellidos: apellido.val(),
					correo: correo.val(),
					fnac: fecha.val()
				};

				$.ajax({
					type:'POST',
					url: host+"user",
					data:data	
				}).success(function(data){
				

					if (data.status	== "success"){
						$("#alerta_crear_usuario").text("Se Guardo con Exito");
						setTimeout(function(){ location.reload(); }, 1000);
					}
					else
					{
						$("#alerta_crear_usuario").text("El Usuario ya Existe");
					}

				}).error(function(data){
					$("#alerta_crear_usuario").text("Error");
					
				});

			}
			else	{
				$("#alerta_crear_usuario").text("Revice el Password que Sean Iguales");
			}
		}
		else{
			$("#alerta_crear_usuario").text("Revice los Campos no esten Vacios");
		}


	});

	function limpiar_todo()
	{

	}

});