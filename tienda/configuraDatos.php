<?php
    session_start();  
    include("funciones.php");
    sacaUsuarioN_A();
    $conn = ConectarBD();
    $msg = $msg2 ="";
    
    if(isset($_POST["usuario"]) && isset($_POST["nombre"])
    && isset($_POST["correo"]) && isset($_POST["telefono"])
    && isset($_POST["apellido"]))
    {
        $usuario = $_POST["usuario"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];

        $qry = "update usuarios set usuario='$usuario', nombre='$nombre', apellidoPaterno='$apellido', correo='$correo', telefono='$telefono' where usuario='$usuario'";
        if(mysqli_query($conn,$qry))
            $msg = "<p class='pCorrecto'>Datos actualizados exitosamente</p>";
        else
            $msg = "<p class='pError'>Ocurrió un error y no se pudo hacer la modificación</p>";
    }

    if(isset($_GET["idD"]) && $_GET["idD"] != "")
    {
        $qry = "delete from direcciones where idDireccion=".$_GET['idD']." and idUsuario=".$_SESSION["idUsuario"];
        //echo $_GET['idD'];
        //echo $qry;
        mysqli_query($conn,$qry);
        header("location:http://localhost/tienda/configuraDatos.php");
    }

    if(isset($_POST["idU"]))
    {
        $flag=true;
        if($_SESSION["tipo"]=="Administrador")
        {
            $qry = "select count(*) nAdmins from usuarios where tipo = 'Administrador'";
            $res = mysqli_query($conn,$qry);
            $reg = mysqli_fetch_array($res);
            if($reg["nAdmins"]<=1)
            {
                $msg2 = "<p class='pError'>El sistema no puede quedarse sin una cuenta de anministrador</p>";
                $flag=false;
            }
        }
        if($flag)
        {
            $qry = "delete from usuarios where idUsuario='".$_POST["idU"]."'";
            mysqli_query($conn,$qry);
            $qry = "delete from carrito where idUsuario='".$_POST["idU"]."'";
            mysqli_query($conn,$qry);
            $qry = "delete from comentarios where idUsuario='".$_POST["idU"]."'";
            mysqli_query($conn,$qry);
            $qry = "delete from direcciones where idUsuario='".$_POST["idU"]."'";
            mysqli_query($conn,$qry);
            header("Location:http://localhost/tienda/logout.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        relacionesEstiloBarra();
        relacionesFormulario();
        relacionesSeccionDL();
    ?>
    
    <script>
        function valida()
        {
            return true;
        }
        function verificaDatos()
        {
            var usuario = document.getElementById("usuario");
            var correo = document.getElementById("correo").value;
            var nombre = document.getElementById("nombre");
            var apellido = document.getElementById("apellido").value;
            var telefono = document.getElementById("telefono");
            
            var usuario_ = document.getElementById("usuario_").value;
            var correo_ = document.getElementById("correo_").value;
            var nombre_ = document.getElementById("nombre_").value;
            var apellido_ = document.getElementById("apellido_").value;
            var telefono_ = document.getElementById("telefono_").value;
            
            if(usuario.value=="")
            {
                alert("El campo Usuario no puede estar vacio");
                usuario.value = usuario_;
                return false;
            }
            if(nombre.value=="")
            {
                alert("El campo Nombre no puede estar vacio");
                nombre.value = nombre_;
                return false;
            }
            if(telefono.value=="")
            {
                alert("El campo Telefono no puede estar vacio");
                telefono.value = telefono_;
                return false;
            }

            usuario = usuario.value;
            nombre = nombre.value;
            telefono = telefono.value;

            if(usuario==usuario_ && nombre==nombre_ 
            && telefono==telefono_ && correo==correo_
            && apellido==apellido_)
            {
                alert("No se realizó ninguna modificación, la información no será afectada");
                return false;
            }
        }
    </script>
    
</head>
  
<body>
  <!--aquí va la barra-->
    <?php
        encabezado();
    ?>
    
    <section class="contenidoPagina">
        <h1>Configuración</h1>
        <hr>
            <!--Recuperar tupla-->
            <?php
                $qry = "select usuario, nombre, apellidoPaterno, correo, telefono, contrasenia from usuarios where idUsuario = '". $_SESSION["idUsuario"] ."'";
                $res = mysqli_query($conn,$qry);
                $reg = mysqli_fetch_array($res);
                $nombre = $reg["nombre"];
                $apellido = $reg["apellidoPaterno"];
                $correo = $reg["correo"];
                $telefono = $reg["telefono"];
                $usuario = $reg["usuario"];
            ?>
        <br>
            <!--Se despliegan inputs de indormación de cuenta-->
        <form class='formulario' method="post" action="configuraDatos.php" onsubmit="return verificaDatos()">
            <h2>Datos de cuenta</h2>
        <?php
            echo "<label class='campo'> <p>Usuario:</p> <input type='text' id='usuario' name='usuario' value='$usuario'> </label>
                    <input class='oculto' type='text' id= 'usuario_' value='$usuario'>";
            echo "<label class='campo'> <p>Correo:</p> <input type='text' id='correo' name='correo' value='$correo'> </label>
                    <input class='oculto' type='text' id= 'correo_' value='$correo'>";
            
            echo "<a class='liga' href='cambiarContraseña.php'>Cambiar contraseña</a> <br><br><br>
                    <h2>Datos personales</h2>";

            echo "<label class='campo'> <p>Nombre:</p> <input type='text' id='nombre' name='nombre' value='$nombre'> </label>
            <input class='oculto' type='text' id= 'nombre_' value='$nombre'>";
            echo "<label class='campo'> <p>Apellido:</p> <input type='text' id='apellido' name='apellido' value='$apellido'> </label>
            <input class='oculto' type='text' id= 'apellido_' value='$apellido'>";
            echo "<label class='campo'> <p>Telefono:</p> <input type='text' id='telefono' name='telefono' value='$telefono'> </label>
            <input class='oculto' type='text' id= 'telefono_' value='$telefono'>";

            if($msg!="")
            {
                echo$msg;
            }
        ?>
            
            <input class='enviar' type='submit' value='Guardar'>
        </form>            
        <br>
        <br>

        <h2>Direcciones</h2>
        <hr>
        <div class="seccion">
            <?php
                $qry = "select idDireccion, nombre, numero, calle, codigoPostal, colonia, telefono from direcciones where idUsuario = '".$_SESSION["idUsuario"]."'";
                $rs = mysqli_query($conn,$qry);
                if(mysqli_num_rows($rs)>0)
                {
                    while($reg = mysqli_fetch_array($rs))
                    {
                        echo "<div class='datosLiga'>
                                <div class='datos'>
                                    <h4>".$reg["nombre"]."</h4> 
                                    <p>Colonia: ".$reg["colonia"]."</p>
                                    <p>CP: ".$reg["codigoPostal"]."</p>
                                    <p>Calle: ".$reg["calle"]."</p>
                                    <p>Numero: ".$reg["numero"]."</p>
                                    <p>Tel: ".$reg["telefono"]."</p>
                                </div>
                                <a class='liga' href='configuraDatos.php?idD=".$reg['idDireccion']."'>Eliminar</a>
                            </div><hr>";

                    }
                }
            ?>
            <br>
            <a class="liga" href="agregarDireccion.php">Agregar dirección</a>
        </div>  
        
        
        <?php
        if($msg2!="")
            echo$msg2;
        if($_SESSION["tipo"]=="Administrador")
        {
        ?>
        <br>
        <br>
        <a class="liga" href="administrarUsuarios.php">Administrar usuarios</a>
        <?php
        }
        ?>
        <br><br>
        <a class="liga" href="javascript:confirmacion('formID')">Eliminar cuenta</a>
        
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>

        <form id="formID" action="configuraDatos.php" method="POST">
            <input name="idU" type="hidden" value="<?php echo$_SESSION["idUsuario"];?>">
        </form>

        <?php
            confirmacion();
        ?>
    </section>
    <?php
            footer();
        ?>
    
</body>
</html>