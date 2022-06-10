<?php
    session_start();
    include("funciones.php");
    sacaUsuario();
    $conn = ConectarBD();
    $msg="";
    if(isset($_POST["nuevoTipo"]))
    {
        $qry = "insert into tipos (tipo) values ('".$_POST["nuevoTipo"]."')";
        if(mysqli_query($conn,$qry))
            $msg = "<p class='pError'>Ocurrió un error y no se pudo registrar el dato</p>";
    }
    if(isset($_POST["nuevaEtiqueta"]))
    {
        $qry = "insert into etiquetas (etiqueta) values ('".$_POST["nuevaEtiqueta"]."')";
        if(!mysqli_query($conn,$qry))
            $msg = "<p class='pError'>Ocurrió un error y no se pudo registrar el dato</p>";
    }

    if(isset($_GET["tipo"]))
    {
        $qry = "delete from tipos where tipo = '".$_GET["tipo"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from tipoproducto where tipoProducto = '".$_GET["tipo"]."'";
        mysqli_query($conn,$qry);
    }
    if(isset($_GET["etiqueta"]))
    {
        $qry = "delete from etiquetas where etiqueta = '".$_GET["etiqueta"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from etiquetaproducto where etiquetaProducto = '".$_GET["etiqueta"]."'";
        mysqli_query($conn,$qry);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        //ignoraCacheCss();
        relacionesEstiloBarra();
        relacionesFormulario();
        relacionesSeccionDL();
    ?>

    <script>

        function validarT()
        {
            var tipo = document.getElementById("nuevoTipo").value;
            if(tipo=="")
            {
                return false;
            }
        }

        function validarE()
        {
            var etiqueta = document.getElementById("nuevaEtiqueta").value;
            if(etiqueta=="")
            {
                return false;
            }
        }

        function verificaReenvio()
        {
            if(window.history.replaceState)
            {
                window.history.replaceState(null,null,window.location.href);
            }
        }
    </script>
</head>

<body onload="verificaReenvio()">
    <?php
        encabezado();
    ?>
    <div class="contenidoPagina">

        <h2>Administrar categorias</h2>
        <hr>
        <br>
        <section class="seccion">
        <h3>Tipos de comida</h3>
        <?php
        $qry = "select * from tipos";
        $res = mysqli_query($conn,$qry);
        if(mysqli_num_rows($res)>0)
        {
            while($reg=mysqli_fetch_array($res))
            {
                echo "<div class='datosLiga'>
                        <div class='datos'>
                            <h4>".$reg["tipo"]."</h4>
                        </div>
                        <a class='liga' href='administrarCategorias.php?tipo=".$reg["tipo"]."'> Eliminar</a>
                    </div> <hr>";
            }
        }
        else
            echo "<p class='p'>Aún no hay categorias de comida</p>";

        if(isset($_POST["nuevoTipo"]) && $msg!="")
            echo $msg;
        ?>
        </section>
        
        <form class="formulario" method="POST" action="administrarCategorias.php" onsubmit="return validarT()">
            <div class="campoB">
                <p>Agregar nuevo tipo de comida:</p>
                <div>
                    <input class="texto" type='text' id='nuevoTipo' name='nuevoTipo'>
                    <input class='enviar' type='submit' value='Agregar'>
                </div>
            </div>
        </form>   
        <br>

        <section class="seccion">
        <h3>Etiquetas</h3>
        <?php
        $qry = "select * from etiquetas";
        $res = mysqli_query($conn,$qry);
        if(mysqli_num_rows($res)>0)
        {
            while($reg=mysqli_fetch_array($res))
            {
                echo "<div class='datosLiga'>
                        <div class='datos'>
                            <h4>".$reg["etiqueta"]."</h4>
                        </div>
                        <a class='liga' href='administrarCategorias.php?etiqueta=".$reg["etiqueta"]."'> Eliminar</a>
                    </div> <hr>";
            }
        }
        else
            echo "<p class='p'>Aún no hay etiquetas de comida</p>";
        
        if(isset($_POST["nuevoTipo"]) && $msg!="")
            echo $msg;
        ?>
        </section>

        
        <form class="formulario" method="POST" action="administrarCategorias.php" onsubmit="return validarE()">
            <div class="campoB">
                <p>Agregar nueva etiqueta:</p>
                <div>
                    <input class="texto" type='text' id='nuevaEtiqueta' name='nuevaEtiqueta'>
                    <input class='enviar' type='submit' value='Agregar'>
                </div>
            </div>
        </form>   
        <br>
        
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </div>
    <?php
        footer();
    ?>
</body>
</html>