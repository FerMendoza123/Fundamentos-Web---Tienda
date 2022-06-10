<?php
    session_start();
    include("funciones.php");
    sacaUsuario();
    $conn=conectarBD();


    if(isset($_GET["slider"]))
    {
        if($_GET["slider"]=="true")
        {
            $qry = "update promociones set slider = 1 where idPromocion='".$_GET["idP"]."'";
        }
        else
        {
            $qry = "update promociones set slider = 0 where idPromocion='".$_GET["idP"]."'";
        }
        mysqli_query($conn,$qry);
    }
    if(isset($_POST["idP"]))
    {
        $qry = "update promociones set descuento = '".$_POST["porcentaje"]."' where idPromocion='".$_POST["idP"]."'";       
        mysqli_query($conn,$qry);
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }

    if(isset($_GET["idPE"]))
    {
        $qry = "delete from promociones where idPromocion='".$_GET["idPE"]."'";
        mysqli_query($conn,$qry);
        header("Location:".$_SERVER["HTTP_REFERER"]);
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
        relacionesSeccionDL();
        relacionesFormulario();
    ?>
    <script>
        function muestraForm(idForm)
        {
            nombreForm = nombreDiv = idForm;
            var form = document.getElementById(nombreForm);
            form.style.display = "block";
        }
        function valida()
        {
            if(nombreDiv=="confirmacionEnvio")
                return true;
            else{
                var texto=document.getElementById(nombreForm).value;
                if(texto!="")
                    return true;
            }
        }
    </script>
    <style>
        .formC{
            display: none;
        }
        .datosLiga
        {
            flex-flow: row;
            flex-wrap: wrap;
            width: 100%;
        }
        .datosLiga img
        {
            width: 200px;
        }
        .datosLiga .datos{
            width: fit-content;
        }

        .datosLiga .liga{
            float: left;
            margin: 6px;
        }

        .ligas{
            display: flex;
            flex-flow: column;
            margin-right: 30px;
        }
    </style>
</head>


<body>
    <?php
        encabezado();
    ?>
    <div class="contenidoPagina">
        <h2>Administrar promociones</h2>
        <hr>
        <br>
        <section class='seccion'>
            <?php
                $qry = "select p.slider, p.idPromocion, p.descuento, prd.nombre, prd.precio, prd.idProducto
                from promociones as p inner join productos as prd where p.idProducto=prd.idProducto";
                $promos = mysqli_query($conn,$qry);
                if(mysqli_num_rows($promos)>0)
                {
                    while($promoProd = mysqli_fetch_array($promos))
                    {

                        echo"
                        <div class='datosLiga'>
                            <img src='imagenProducto.php?idIp=".$promoProd["idProducto"]."'>
                            <div class='datos'>
                                <h3>".$promoProd["nombre"]."</h3>
                                <h4>Precio:</h4>$".$promoProd["precio"]."
                                <h4>Descuento:</h4>".$promoProd["descuento"]."%
                                <h4>Precio con descuento:</h4>$".($promoProd["precio"]*(100-$promoProd["descuento"])/100)."
                            </div>
                            <div class='ligas'>
                                <a class='liga' href=\"javascript:muestraForm('form".$promoProd["idPromocion"]."')\">Modificar</a>
                                <a class='liga' href=\"administrarPromociones.php?idPE=".$promoProd["idPromocion"]."\">Eliminar</a>";
                            if($promoProd["slider"]==0)
                            {
                            echo"<a class='liga' href=\"administrarPromociones.php?slider=true&idP=".$promoProd["idPromocion"]."\">Agregar al slider</a>";
                            }
                            else
                            {
                            echo"<a class='liga' href=\"administrarPromociones.php?slider=false&idP=".$promoProd["idPromocion"]."\">Quitar del slider</a>";
                            }

                            echo"<form class='formC' id='form".$promoProd["idPromocion"]."' method='post' action='administrarPromociones.php'>
                                    Porcentaje:
                                    <input type='number' name='porcentaje' value='".$promoProd["descuento"]."' min='1'>
                                    <div>
                                        <input class='enviar' type='button' value='Guardar' onclick='confSi()'>
                                        <input class='enviar' type='button' value='Cancelar' onclick='confNo()'>
                                    </div>
                                    <input type='hidden' name='idP' value='".$promoProd["idPromocion"]."'>
                                </form>
                            </div>
                        </div>
                        <hr>";
                    }
                }
                else
                {
                    echo "<p>Aun no existen promociones, puedes agregarlas desde cada producto</p>";
                }
            ?>
        </section>
        <a class='liga' href="principal.php">Pagina principal</a>
    </div>
    <?php
        footer();
    ?>
</body>
</html>