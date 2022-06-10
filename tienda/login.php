<?php
    session_start();
    if(isset($_SESSION["usuario"]))
    {
        header("Location:http://localhost/tienda/principal.php");
    }
    include("funciones.php");
    $msg="";
    if(isset($_POST["usuario"]) && isset($_POST["contrasenia"])
    && $_POST["usuario"]!="" && $_POST["contrasenia"]!="")
    {
        $usuario = $_POST["usuario"];
        $contra = $_POST["contrasenia"];

        //$usuario = mysqli_real_escape_string($_POST["usuario"]);
        //$contra = mysqli_real_escape_string($_POST["contrasenia"]);

        $conn = conectarBD();
        $qry = "select idUsuario, tipo, contrasenia from usuarios where usuario=BINARY'$usuario'";

        $rs = mysqli_query($conn,$qry);

        if(mysqli_num_rows($rs)>0)
        {
            $usr = mysqli_fetch_array($rs);
            if($usr["contrasenia"]==$contra)
            {
                creaSesion($usuario,$usr["tipo"],$usr["idUsuario"]);
                $cadena="";

                header("Location:http://localhost/tienda/principal.php?");
            }
            else
                $msg = "<p class='pError'>La contraseña es incorrecta</p>";
        }
        else
            $msg = "<p class='pError'>El usuario no existe</p>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <?php
        relacionesFormulario();
    ?>
    <script>
        function verificaLogin()
        {
            var usuario = document.getElementById("usuario");
            var contra = document.getElementById("contrasenia");

            if(usuario.value=="" || contra.value=="")
            {
                alert("Todos los campos deben estar completos");
                return false;
            }
        }
    </script>
</head>


<body>
    <section class="contenidoPagina">
        <h1>Inicio de sesión</h1>
        <hr>
        <form class='formulario' method="post" action="login.php" onsubmit="return verificaLogin()">
            <label class='campo'><p>Usuario:</p><input id="usuario" name="usuario" type="text" placeholder="Anota tu usuario"></label>
            <br>
            <label class='campo'><p>Contraseña:</p><input id="contrasenia" name="contrasenia" type="password" placeholder="Anota tu Contrasenia"></label>
            <br>
            <?php 
                echo $msg; 
            ?>
            <input class='enviar' type="submit" value="Entrar">
            <br>
            <a class="liga" href="registro.php" >Crear una cuenta</a>
        </form>    
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>
</body>
</html>