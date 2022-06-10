<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    $conn = conectarBD();

    if(isset($_POST["idA"]))
    {
        $qry = "update carrito set cantidad='".$_POST["cantidad"]."' where idArticulo = '".$_POST["idA"]."'";
        mysqli_query($conn,$qry);
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }
    if(isset($_GET["idAE"]))
    {
        $qry = "delete from carrito where idArticulo = '".$_GET["idAE"]."'";
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
    <style>
        .formC{
            display: none;
        }
        .datosLiga
        {
            flex-flow: row;
            flex-wrap: wrap;
            width: 100%;
            justify-content: space-around;
        }
        .datosLiga img
        {
            width: 200px;
        }
        .datosLiga .datos{
            width: 200px;
        }

        .datosLiga .liga{
            float: left;
            margin: 6px;
        }

        .ligas{
            display: flex;
            flex-flow: column;
        }
        #total{
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            margin: 20px;
        }

        #comprar{
            text-decoration: none;
            float: right;
            margin: 20px;
        }
    </style>
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
</head>

<body>
    <?php
        encabezado();
    ?>

    <div class="contenidoPagina">
        <h1>Carrito</h1>
        <hr>
        <section class='seccion'>
            <?php
                $qryC = "select c.idArticulo, c.idProducto, c.ingrediente, c.cantidad,
                p.nombre, p.precio from carrito as c inner join productos as p where c.idProducto=p.idProducto and c.idUsuario='".$_SESSION["idUsuario"]."'";
                $carrito = mysqli_query($conn,$qryC);
                if(mysqli_num_rows($carrito)>0)
                {
                    $total=0;
                    while($articulo = mysqli_fetch_array($carrito))
                    {
                        $qryA = "select descuento from promociones where idProducto='".$articulo["idProducto"]."'";
                        $descuento = mysqli_query($conn, $qryA);
                        $totalP=0;
                        echo"
                        <div class='datosLiga'>
                            <img src='imagenProducto.php?idIp=".$articulo["idProducto"]."'>
                            <div class='datos'>
                                <h3>".$articulo["nombre"]."</h3>
                                <h4>Precio: </h4>$".$articulo["precio"];
                            if(mysqli_num_rows($descuento)>0)
                            {
                                $descuento = mysqli_fetch_array($descuento);
                                $precio = $articulo["precio"]*(100-$descuento["descuento"])/100;
                                echo"<h4>Descuento: </h4>".$descuento["descuento"]."%
                                <h4>Precio con descuento: </h4>$".$precio;
                                $totalP=$precio*$articulo["cantidad"];
                            }
                            else
                                $totalP=$articulo["precio"]*$articulo["cantidad"];
                            $total += $totalP;
                            echo"
                                <h4>Cantidad: </h4>".$articulo["cantidad"]."
                                <h4>Precio final: $</h4>$totalP
                            </div>
                            <div class='ligas'>
                                <a class='liga' href=\"javascript:muestraForm('form".$articulo["idArticulo"]."')\">Modificar</a>
                                <a class='liga' href=\"carrito.php?idAE=".$articulo["idArticulo"]."\">Eliminar</a>

                                <form class='formC' id='form".$articulo["idArticulo"]."' method='post' action='carrito.php'>
                                    Cantidad:
                                    <input type='number' name='cantidad' value='".$articulo["cantidad"]."' min='1'>
                                    <div>

                                        <input class='enviar' type='button' value='Guardar' onclick='confSi()'>
                                        <input class='enviar' type='button' value='Cancelar' onclick='confNo()'>

                                    </div>
                                    <input type='hidden' name='idA' value='".$articulo["idArticulo"]."'>
                                </form>
                            </div>
                        </div>
                        <hr>";
                    }
                    echo"<div id='total'>
                            <h4>Total: </h4>
                            <h4>$$total</h4>
                        </div>
                        <a id='comprar' class='enviar' href='compra.php?idC=compra'>Comprar</a> ";
                }
                else
                {
                    echo "<p>No has agregado productos al carrito</p>";
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