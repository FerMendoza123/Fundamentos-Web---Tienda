<?php
    session_start();
    include("funciones.php");
    $conn = conectarBD();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        ignoraCacheCss();
        relacionesEstiloBarra();
        relacionesSeccionDL();
        relacionesFormulario();
        relacionesProducto();
    ?>
    <style>
        .contenidoPagina{
            display: flex;
            flex-flow: row;
            flex-wrap: nowrap;
            justify-content: space-around;
        }
        #filtrado
        {
            display: flex;
            flex-flow: column;
            flex-wrap: wrap;
        }

        .carrito
        {	
            padding:0px;
            font-size:0em;			
            transition:all .5s ease;
            position:absolute;
            background-color:#8080FF;
            color:white;
        }
        .producto:hover .carrito
        {
            padding:10px;
            border-radius:100px;
            font-size: 2em;
        }

    </style>
</head>

<body>
    <?php
        encabezado();
    ?>
    <script>
        var barra = document.getElementById("buscar");
        barra.setAttribute("value",<?php echo "'".$_GET["buscar"]."'";?>);
    </script>
    <div class='contenidoPagina'>

        <form id="filtrado" method="get" action="busqueda.php">
            <h4>Tipo</h4>
            <?php 
            $qry="select * from tipos";
            $res=mysqli_query($conn,$qry);

        if(mysqli_num_rows($res)>0)
        {
            while($reg=mysqli_fetch_array($res))
            {
                if(!isset($_GET[$reg["tipo"]]))
                    echo "<label class='campoC'> <input type='checkbox' name='".$reg["tipo"]."'>".$reg["tipo"]."</label>";
                else
                    echo "<label class='campoC'> <input type='checkbox' checked name='".$reg["tipo"]."'>".$reg["tipo"]."</label>";
            }
        }
        else
                echo "<p class='p'>Aun no hay tipos de comida</p>";
            ?>
            <h4>Etiqueta</h4>
            <?php
            $qry="select * from etiquetas";
            $res=mysqli_query($conn,$qry);

        if(mysqli_num_rows($res)>0)
        {
            while($reg=mysqli_fetch_array($res))
            {
                if(!isset($_GET[$reg["etiqueta"]]))
                    echo "<label class='campoC'> <input type='checkbox' name='".$reg["etiqueta"]."'>".$reg["etiqueta"]."</label>";
                else
                    echo "<label class='campoC'> <input type='checkbox' checked name='".$reg["etiqueta"]."'>".$reg["etiqueta"]."</label>";
            }
        }
        else
            echo "<p class='p'>Aun no hay etiquetas</p>";
            echo "<input name='buscar' type='hidden' value='".$_GET["buscar"]."'>";
        ?>
        <input name="b" class='enviar' type='submit' value='Aplicar'>

        </form>

        <section id='productos'>
    <?php
        if(isset($_GET["buscar"]))
        {
            $conn = conectarBD();
            $busqueda = $_GET["buscar"];
            $qry = '';
            if(isset($_GET["b"]))
            {
                $qryE = "select * from etiquetas";
                $etiquetas = mysqli_query($conn,$qryE);
                $etiBool = false;
                $qry1 = "select distinct p.nombre, p.precio, p.idProducto from productos as p inner join etiquetaproducto as ep where ep.idProducto=p.idProducto and LOCATE('$busqueda',p.nombre)>0 and (";
                while($etiqueta = mysqli_fetch_array($etiquetas))
                {
                    if(isset($_GET[$etiqueta["etiqueta"]])){
                        if($etiBool){
                            $qry1 = $qry1 . " ||";
                        }
                        $qry1 = $qry1 . " ep.etiquetaProducto = '".$etiqueta["etiqueta"]."'";
                        $etiBool=true;
                    }
                }
                $qry1 = $qry1 .")";
                
                $qryT = "select * from tipos";
                $tipos = mysqli_query($conn,$qryT);
                $tipoBool = false;
                $qry2 = "select distinct p.nombre, p.precio, p.idProducto from productos as p inner join tipoproducto as tp where tp.idProducto=p.idProducto and LOCATE('$busqueda',p.nombre)>0 and (";
                while($tipo = mysqli_fetch_array($tipos))
                {
                    if(isset($_GET[$tipo["tipo"]])){
                        if($tipoBool){
                            $qry2 = $qry2 . " ||";
                        }
                        $qry2 = $qry2 . " tp.tipoProducto = '".$tipo["tipo"]."'";
                        $tipoBool=true;
                    }
                }
                $qry2 = $qry2 .")";

                if($tipoBool && $etiBool)
                {
                    $qry = $qry1." intersect " .$qry2;
                }
                else
                {
                    if($etiBool)
                        $qry = $qry1;
                    else
                    {
                        if($tipoBool)
                            $qry = $qry2;
                        else
                            $qry = "select distinct p.nombre, p.precio, p.idProducto from productos as p where LOCATE('$busqueda',p.nombre)>0";
                    }
                }
            }
            else 
            {
                $qry = "select distinct p.nombre, p.precio, p.idProducto from productos as p where LOCATE('$busqueda',p.nombre)>0";
            }
            //echo $qry;
            
            $res = mysqli_query($conn,$qry);   
            //echo mysqli_errno($conn);              
            if(mysqli_num_rows($res)>0)       
                while($reg=mysqli_fetch_array($res))
                {
                    
                    echo "<div class='producto'>
                            <a href='producto.php?idP=".$reg["idProducto"]."&buscar=$busqueda'>
                                <img src='imagenProducto.php?idIp=".$reg["idProducto"]."' alt='Imagen'>
                            </a>
                            <a class='carrito' href='agregarCarrito.php?idP=".$reg["idProducto"]."&cantidad=1'><i class='fas fa-cart-plus'></i></a>
                            <div class='datos'>
                                <p>".$reg["nombre"]."</p>";
                                
                                $qryPromo = "select descuento from promociones where idProducto=".$reg["idProducto"];
                                $promo=mysqli_query($conn,$qryPromo);
                                if(mysqli_num_rows($promo)>0)
                                {
                                    $descuento = mysqli_fetch_array($promo);
                                    $pFinal = $reg["precio"]*(100-$descuento["descuento"])/100;
                                    echo"<p style='text-decoration:line-through; display:inline-block;'>$ ".$reg["precio"]."</p>
                                        <p style='color:red; display:inline-block;' id='promoDes'>-".$descuento["descuento"]."%</p>
                                        <p>$ $pFinal</p>";
                                }
                                else
                                    echo "<p>$ ".$reg["precio"]."</p>";

                        echo"</div>
                        </div>";
                }
            else
                echo "<p>No se encontraron productos coincidentes</p>";
        }
    ?>
        </section>
        
    </div>
    <section style="margin:0px 0px 5% 5%;">
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>
    <?php
        footer();
    ?>
</body>
</html>