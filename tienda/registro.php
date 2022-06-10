<?php
    session_start();
    include("funciones.php");

    //En esta variable se almacenara un mensaje de error que pueda surgir al usuario
    $msg="";
    //Comprobamos que se ha enviado el formulario (por eso uso post)
    if(isset($_POST["txtUs"]) && isset($_POST["txtNom"]) 
    && isset($_POST["txtCon"]) && isset($_POST["txtConCon"])
    && isset($_POST["txtApellido"]) && isset($_POST["txtNum"])
    && isset($_POST["txtCor"]))
    {
        //Recupero datos
        $usuario = $_POST["txtUs"];
        $nombre = $_POST["txtNom"];
        $apellido = $_POST["txtApellido"];
        $numero = $_POST["txtNum"];
        $correo = $_POST["txtCor"];
        $contra = $_POST["txtCon"];

        
        $conn = ConectarBD();

        $qry = "insert into usuarios (contrasenia, tipo, nombre, apellidoPaterno, telefono, usuario, correo)
                    values ('$contra','Normal','$nombre','$apellido','$numero','$usuario', '$correo')";

        if(!mysqli_query($conn,$qry))
        {
            //echo mysqli_errno($conn);
            if(mysqli_errno($conn)=="1062")
            {
                $msg = "<p class='pError'>El usuario ya existe, intenta de nuevo</p>";
            }
            $msg = "<p class='pError'>Ocurrio un error y no se pudo realizar el registro</p>";
        }
        else
        {
            $qry = "select idUsuario from usuarios where usuario='$usuario'";
            $rs = mysqli_query($conn,$qry);
            $reg = mysqli_fetch_array($rs);
            creaSesion($usuario,"Normal",$reg["idUsuario"]);
            header("Location:http://localhost/tienda/principal.php");
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
        relacionesFormulario();
    ?>

    <script>
        function verificaDatos()
        {
            
            var usuario = document.getElementById("txtUs").value;
            var nombre = document.getElementById("txtNom").value;
            var apellido = document.getElementById("txtApellido").value;
            var numero = document.getElementById("txtNum").value;
            var contra = document.getElementById("txtCon").value;
            var conContra = document.getElementById("txtConCon").value;

            if(usuario==""||nombre==""||numero==""||contra==""||conContra=="")
            {
                alert("No es posible continuar con el registro, los campos con asterisco son obligatorios");
                return false;
            }
            if(numero.length<10)
            {
                alert("El numero tiene que estar completo (10 digitos)");
                return false;
            }
            if(contra!=conContra)
            {
                alert("La confirmación de la contraseña es diferente de la contraseña, asegurate de que sean iguales");
                return false;
            }
        }

        function hola()
        {
            alert("asdasd");
        }
    </script>
</head>
<body>
    <section class="contenidoPagina">
        <h1> Pagina de registro de usuarios </h1>
        <hr>
        <form class="formulario" method="post" action="registro.php" onsubmit="return verificaDatos()">
            <label class='campo'><p>*Usuario:</p> <input type="text" id="txtUs" name="txtUs"></label> 
            <br>
            <label class='campo'><p>*Nombre:</p> <input type="text" id="txtNom" name="txtNom"></label>
            <br>
            <label class='campo'><p>Apellido Paterno:/<p> <input type="text" id="txtApellido" name="txtApellido"></label>
            <br>
            <label class='campo'><p>*Numero celular:</p> <input type="number" id="txtNum" name="txtNum"></label>
            <br>
            <label class='campo'><p>Correo electronico:</p> <input type="text" id="txtCor" name="txtCor"></label>
            <br>
            <label class='campo'><p>*Contraseña:</p> <input type="password" id="txtCon" name="txtCon"></label>
            <br>
            <label class='campo'><p>*Confirmar contraseña:</p> <input type="password" id="txtConCon" name="txtConCon"></label>
            <br>

            <?php
                if($msg!="")
                    echo$msg;
            ?>

            <input class="enviar" type="submit" value="Registrar">
            <br>
            <a class="liga" href="login.php" >Ya tengo una cuenta</a>
        </form>    
        <br>
        <a class="liga" href="principal.php">Pagina principal</a>
    </section>
    <?php
            footer();
        ?>
</body>
</html>