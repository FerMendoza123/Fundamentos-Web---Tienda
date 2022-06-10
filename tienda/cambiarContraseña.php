<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    $msg="";
    if(isset($_POST["contrasenia"]) &&
        isset($_POST["contraseniaN"]) &&
        isset($_POST["contraseniaC"]))
    {
        $contra = $_POST["contrasenia"];
        $contraN = $_POST["contraseniaN"];
        $contraC = $_POST["contraseniaC"];

        if($contraN==$contraC)
        {
            if($contraN!=$contra)
            {
                $conn = conectarBD();
                $qry = "select contrasenia from usuarios where usuario = '".$_SESSION["usuario"]."'";
                $res = mysqli_query($conn,$qry);
                $reg = mysqli_fetch_array($res);
               
                if($contra==$reg["contrasenia"])
                {
                    $qry = "update usuarios set contrasenia = '$contraN' where usuario = '".$_SESSION["usuario"]."'";
                    mysqli_query($conn,$qry);
                    $msg = "<p class='pCorrecto'>La contraseña ha sido cambiada exitosamente</p>";
                }
                else
                {
                    $msg = "<p class='pError'>La contraseña actual es incorrecta</p>";
                }
            }
            else
            {
                $msg = "<p class='pError'>La nueva contraseña no puede ser igual a la anterior</p>";
            }
        }
        else
        {
            $msg = "<p class='pError'>La nueva contraseña y la confirmación deben ser iguales</p>";
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
    ?>
    <script>
        function valida()
        {
            contra = document.getElementById("contrasenia").value;
            contraN = document.getElementById("contraseniaN");
            contraC = document.getElementById("contraseniaC");
            
            if(contra=="" || contraC.value=="" || contraN.value=="")
            {
                alert("Todos los campos deben estar completos");
                return false;
            }

            if(contraN.value==contra)
            {
                alert("La contraseña nueva no puede ser igual que la anterior");
                return false;
            }
            else if(contraC.value!=contraN.value)
            {
                contraN.value=contraC.value="";
                alert("La nueva contraseña y la confirmación son diferentes");
                return false;
            }
            return true;
        }
    </script>
</head>
<body onload="verificaReenvio()">
    <section class="contenidoPagina">
        <h1>Cambio de contraseña</h1> 
        <hr>
        <form id="formContraseña" class="formulario" method="POST" action="cambiarContraseña.php">
            <label class='campo'><p>Contraseña:</p><input id="contrasenia" name="contrasenia" type="password" placeholder="Contraseña actual"></label>
            <label class='campo'><p>Contraseña nueva:</p><input id="contraseniaN" name="contraseniaN" type="password" placeholder="Contraseña nueva"></label>
            <label class='campo'><p>Confirmar Contraseña:</p><input id="contraseniaC" name="contraseniaC" type="password" placeholder="Confirmar Contraseña"></label>
            <?php 
                if($msg!="")
                    echo$msg;
            ?>
            <br>
            <input class='enviar' type="button" value="Cambiar" onclick="confirmacion('formContraseña')">
        </form>
        <a class="liga" href="configuraDatos.php">Regresar</a>
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>
    <div id="confirmacionEnvio" class="confirmacion">
        <p>¿Estas seguro que quieres realizar esta acción?</p>
        <div>
            <input class="enviar" type="submit" value="Si" onclick="confSi()">
            <input class="enviar" type="submit" value="No" onclick="confNo()">
        </div>
    </div>

</body>
</html>