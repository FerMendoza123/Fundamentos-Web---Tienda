<?php
	//Iniciar sesion en el archivo que llame a esta función
	function encabezado()
	{
		$conn=conectarBD();
		//  <!--------------------------->
		$barra="<div id='encabezado'>

			<img id='logo' src='logo.jpeg' alt='imagenLogotipo'>
				

			<div id='seccionMedia'>
				<form class='formulario' method='get' action='busqueda.php' onsubmit='return validaDatos()'>
					<div class='campoB'>
						<div>
							<input class='texto' type='text' placeholder='Buscar' id='buscar' name='buscar'>
							<input class='enviar' type='submit' value='Buscar'>
						</div>
					</div>
				</form>
				<nav id='menuNav'>";
					//<a href='busqueda.php?buscar?'>Menu</a>
					//<a href='#'>Postres</a>
				$qry="select tipo from tipos";
				$res = mysqli_query($conn,$qry);
				if(mysqli_num_rows($res)>0)
				{
					while($reg = mysqli_fetch_array($res))
					{
						$barra=$barra."<a class='liga' href='busqueda.php?".$reg["tipo"]."=on&buscar=&b='aplicar'>".$reg["tipo"]."</a>";
					}
				}
			$barra=$barra."</nav>
			</div>
			
			<!--Arreglar el espacio de cada elemento en estilos-->
			<section id='espacioIconos'>
				<!--Agregar el onmouseover y onmouseout-->      
				<a id='cuenta' onmouseout='ocultaSM(\"subMenuC\")' onmouseover='mostrarSM(\"subMenuC\",\"cuenta\")' href='#'><i class='fas fa-user'></i></a>
				<!--colocar el enlace a la pagina del carrito-->
				<a id='carrito' href='carrito.php'><i class='fas fa-shopping-cart'></i></a>

				<nav id='subMenuC' onmouseout='ocultaSM(\"subMenuC\")' onmouseover='mostrarSM(\"subMenuC\",\"cuenta\")'>
				<!--Colocar php para opciones de usuario-->";

				if(!isset($_SESSION["usuario"]))
					$barra=$barra."<a href='login.php'>Iniciar sesión</a>
								   <a href='registro.php'>Registrate</a>";
				else
				{
					$barra=$barra."<p>".$_SESSION["usuario"]."</p>
									<a href='verCompras.php'>Ver compras</a>
									<a href='configuraDatos.php'>Configuración</a> 
								   <a href='logout.php'>Cerrar sesión</a>";
					if($_SESSION["tipo"]=="Administrador")
					{
						$barra=$barra."<a href='agregarProducto.php'>Agregar producto</a>";
						$barra=$barra."<a href='administrarCategorias.php'>Administrar categorias</a>";
						$barra=$barra."<a href='administrarPromociones.php'>Administrar promociones</a>";
					}
				}
				$barra = $barra . "</nav>
			</section>
		</div>
		
		<div id='difuminado'></div>";

		echo $barra;
	 /****************************** */
	}

	function confirmacion()
	{
	?>
		<div id="confirmacionEnvio" class="confirmacion">
            <p>¿Estas seguro que quieres realizar esta acción?</p>
            <div>
                <input class="enviar" type="submit" value="Si" onclick="confSi()">
                <input class="enviar" type="submit" value="No" onclick="confNo()">
            </div>
		</div>
	<?php
	}

	function footer()
	{
		echo"<div id='footer'></div>";
	}

	function relacionesEstiloBarra()
	{
		echo "<link href='css/all.css' rel='stylesheet'>";
		echo "<link href='elementos/formularios.css' rel='stylesheet'/>";
		echo "<link href='elementos/encabezado/encabezadoGeneral.css' rel='stylesheet'/>";
		echo "<link href='elementos/encabezado/encabezadoGrande.css' rel='stylesheet' media='screen and (min-width:981px)'/>";
		//echo "<link href='elementos/encabezado/encabezadoMediano.css' rel='stylesheet' media='screen and (min-width:481px) and (max-width: 980px)'/>";
		echo "<link href='elementos/encabezado/encabezadoMediano.css' rel='stylesheet' media='screen and (max-width: 980px)'/>";
		echo "<script src='elementos/encabezado/encabezadoGrande.js'></script>";

		$url= $_SERVER["REQUEST_URI"];
		if($url!="/tienda/principal.php?" && $url!="/tienda/principal.php")
		{
			echo "<style> #difuminado{ opacity: 100%; position:relative;} </style>";
		}
	}

	function relacionesFormulario()
	{
		echo "<link href='css/all.css' rel='stylesheet'>";
		echo "<link href='elementos/formularios.css' rel='stylesheet'/>";
		echo "<script src='elementos/formularios.js'></script>";
	}

	function relacionesSeccionDL()
	{
		echo "<link href='elementos/seccionDL.css' rel='stylesheet'/>";
	}

	function relacionesProducto()
	{
		echo "<link href='elementos/productos/productos.css' rel='stylesheet'/>";
		echo "<link href='elementos/productos/productosC.css' rel='stylesheet' media='screen and (max-width: 481px)'/>";
	}

	//Para esta función el archivo que la llama tiene que haber iniciado sesion
	function creaSesion($usuario,$tipo,$id)
	{
		$_SESSION["usuario"] = $usuario;
		$_SESSION["tipo"] = $tipo;
		$_SESSION["idUsuario"] = $id;
	}

	function conectarBD()
	{
		$c = mysqli_connect("localhost","root","","proyectotecweb");
		return $c;
	}
	//Saca a alguien sin autenticar de la pagina
	function sacaUsuarioN_A()
	{
		if(!isset($_SESSION["usuario"]))
		{
			header("Location:http://localhost/tienda/login.php");
		}
	}
	//Saca a cualquiera no administrador de una pagina solo de administradores
	function sacaUsuario()
	{
		sacaUsuarioN_A();
		if($_SESSION["tipo"]!="Administrador")
		{
			header("Location:http://localhost/tienda/login.php");
		}
	}

	function ignoraCacheCss()
	{
	?>
		<meta http-equiv="Expires" content="0">
		<meta http-equiv="Last-Modified" content="0">
		<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<meta http-equiv="Pragma" content="no-cache">
	<?php
	}
?>