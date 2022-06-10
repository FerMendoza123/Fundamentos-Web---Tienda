<?php
    session_start();
    include("funciones.php");
    sacaUsuario();
    $conn = ConectarBD();
    
    if(isset($_GET["usuario"]) && isset($_GET["tipo"]))
    {
        $conn = conectarBD();
        if($_GET["tipo"]=="Normal")
            $qry = "update usuarios set tipo = 'Administrador' where usuario='".$_GET["usuario"]."'";
        else
            $qry = "update usuarios set tipo = 'Normal' where usuario='".$_GET["usuario"]."'";
        mysqli_query($conn,$qry);
    }

    if(isset($_POST["idU"]))
    {
        $qry = "delete from usuarios where idUsuario='".$_POST["idU"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from carrito where idUsuario='".$_POST["idU"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from comentarios where idUsuario='".$_POST["idU"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from direcciones where idUsuario='".$_POST["idU"]."'";
        mysqli_query($conn,$qry);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function valida()
        {
            return true;
        }
    </script>
    <?php
        //ignoraCacheCss();
        relacionesFormulario();
        relacionesSeccionDL();
        relacionesEstiloBarra();
    ?>
</head>

<body onload="verificaReenvio()">
    <?php
        encabezado();
    ?>
    <div class="contenidoPagina">
        <h2>Administrar usuarios</h2>
        <hr>
        <form class="formulario" method="GET" action="administrarUsuarios.php">
            <div class="campoB">
                <p>Buscar usuario:</p>
                <div>
                    <input class="texto" type='text' id='buscar' name='buscar'>
                    <input class='enviar' type='submit' value='Buscar'>
                </div>
            </div>
        </form>   
        <br>
        <section class="seccion">
        <?php
        if(isset($_GET["buscar"]))
        {
            $busqueda = $_GET["buscar"];
            $qry = "select idUsuario, usuario, tipo from usuarios where LOCATE('$busqueda',usuario)>0 and idUsuario!='".$_SESSION["idUsuario"]."'";
            $res = mysqli_query($conn,$qry);
            if(mysqli_num_rows($res))
            {
                while($reg=mysqli_fetch_array($res))
                {
                    echo "<div class='datosLiga'>
                            <div class='datos'>
                                <h4>".$reg["usuario"]."</h4>
                                <p>Tipo de usuario: ".$reg["tipo"]."</p>
                            </div>
                            <a class='liga' href='administrarUsuarios.php?buscar=".$_GET["buscar"]."&usuario=".$reg["usuario"]."&tipo=".$reg["tipo"]."'> Cambiar rol</a>
                            <a class='liga' href=\"javascript:confirmacion('form".$reg["usuario"]."')\">Eliminar</a>
                        </div> 
                        <form id='form".$reg["usuario"]."' method='post' action='administrarUsuarios.php'>
                            <input name='idU' type='hidden' value='".$reg["idUsuario"]."'>
                        </form>
                        <hr>";
                }
            }
        }
        ?>
        </section>
        <br>
        <br>
        <a class="liga" href="configuraDatos.php">Regresar</a>
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>

        <?php
            confirmacion();
        ?>
    </div>
    <?php
        footer();
    ?>
</body>
</html>