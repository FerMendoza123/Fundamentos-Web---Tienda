<?php
    session_start();
    include("funciones.php");
    sacaUsuario();
    $msg="";
    $conn = conectarBD();


    if(isset($_POST["nombre"]) && isset($_POST["precio"])
     && isset($_POST["ingredientes"]))
    {
        if(!empty($_FILES['imagen']['tmp_name']))
        {
            $tipo = $_FILES['imagen']['type'];

            $nombreTemporal = $_FILES['imagen']['tmp_name'];
            $tamanio = $_FILES['imagen']['size'];

            $fp = fopen($nombreTemporal,'r');
            $contenido = fread($fp,$tamanio);
            fclose($fp);

            $contenido = addslashes($contenido);
            
            $qry = "update productos set imagen='".$contenido."', tipo='".$tipo."' where idProducto='".$_POST["idP"]."'";
        }

        $nombre = $_POST['nombre'];
        $precio = $_POST["precio"];
        $descripcion = $_POST['descripcion'];

        $qry = "update productos set nombre='$nombre', precio='$precio' descripcion='$descripcion' where idProducto='".$_POST["idP"]."'";
        mysqli_query($conn,$qry);
        

        if(isset($_POST["ingredientes"]))
        {
            $qry = "delete from ingredientes where idProducto='".$_POST["idP"]."'";
            mysqli_query($conn,$qry);
            $ingredientes = $_POST["ingredientes"];
            foreach($ingredientes as $ingrediente)
            {
                $qry="insert into ingredientes (idProducto, ingrediente)
                        values ('".$_POST["idP"]."','$ingrediente')";
                mysqli_query($conn,$qry);
            }
        }
        
        $qryTipos = "select * from tipos";
        $qryEtiquetas = "select * from etiquetas";

        
        
        $resT = mysqli_query($conn,$qryTipos);
        $resE = mysqli_query($conn,$qryEtiquetas);

        if(mysqli_num_rows($resT)>0)
        {
            $qry = "delete from tipoproducto where idProducto='".$_POST["idP"]."'";
            mysqli_query($conn,$qry);
            while($reg = mysqli_fetch_array($resT))
            {
                if(isset($_POST[$reg["tipo"]]))
                {
                    $qryTipos = "insert into tipoproducto (idProducto, tipoProducto) 
                                values ('".$_POST["idP"]."','".$reg["tipo"]."')";
                    mysqli_query($conn,$qryTipos);
                }
            }
        }
        
        if(mysqli_num_rows($resE)>0)
        {
            $qry = "delete from etiquetaproducto where idProducto='".$_POST["idP"]."'";
            mysqli_query($conn,$qry);
            while($reg = mysqli_fetch_array($resE))
            {
                if(isset($_POST[$reg["etiqueta"]]))
                {
                    $qryEtiquetas = "insert into etiquetaproducto (idProducto, etiquetaProducto) 
                                values ('".$_POST["idP"]."','".$reg["etiqueta"]."')";
                    mysqli_query($conn,$qryEtiquetas);
                }
            }
        }
        header("Location:http://localhost/tienda/producto.php?idP=".$_POST["idP"]."");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .ingre
        {
            display: flex;
            margin: auto;
            width: 39%;
            justify-content: space-between;
        }
        .ingre .enviar
        {
            margin: 0;
            padding: 0;
        }
        #agregar{
            width: 30%;
        }
        #imagenActual
        {
            display: flex;
            justify-content: center;
        }
        #imagenActual img
        {
            width: 200px;
        }
    </style>
    <?php
        relacionesFormulario();
        relacionesEstiloBarra();
    ?>
</head>

<body>

<section class="contenidoPagina">
    <a class='liga' href="producto.php?idP=<?php echo $_GET["idP"];?>">Regresar</a>
        <h2>Modificar Producto</h2>
        <hr>
        <br>
        <br>
        <?php
            $qry = "select * from productos where idProducto=".$_GET["idP"];
            $res = mysqli_query($conn,$qry);
            $prod = mysqli_fetch_array($res);
        ?>
        <form class="formulario" action="modifica.php" method="post" enctype="multipart/form-data">
            <label class='campo'><p>*Nombre:</p> <input type="text" id="nombre" name="nombre" value="<?php echo $prod["nombre"]; ?>"></label>
            <label class='campo'><p>*Precio(pesos):</p> <input type="number" id="precio" name="precio"value="<?php echo $prod["precio"]; ?>"></label>
            <label class='campo'><p>Descripción:</p> <input type="text" id="descripcion" name="descripcion" value="<?php echo $prod["descripcion"]; ?>"></label> 
            <?php
            echo"<div id='imagenActual'>
                <img src='imagenProducto.php?idIp=".$prod["idProducto"]."' alt='Imagen'>
            </div>";
            ?>
            <label class='campo'><p>*Imagen:</p> <input type="file" id="imagen" name="imagen"></label>
            <h3 class="h">*Ingrediente/Sabor</h3>

            <div id="ingredientes">
            <?php
                $qryIngre = "select ingrediente from ingredientes where idProducto = '".$_GET["idP"]."'";
                $ingredientes = mysqli_query($conn,$qryIngre);
                while($ingrediente = mysqli_fetch_array($ingredientes))
                {
                    echo"<div id='".$ingrediente["ingrediente"]."' class='ingre'>
                            <p>".$ingrediente["ingrediente"]."</p>
                            <input class='enviar' type='button' value='Eliminar' onclick=\"eliminarIngrediente('".$ingrediente["ingrediente"]."')\">
                            <input class='oculto' type='text' name='ingredientes[]' value='".$ingrediente["ingrediente"]."'>
                        </div>";
                        
                }
            ?>
            <script>
                var ingredientes = document.getElementById("ingredientes");
                function agregarIngrediente()
                {
                    var ingrediente = document.getElementById("ingrediente").value;

                    if(ingrediente!="")
                    {
                        var div = document.createElement("div");
                        div.setAttribute("id",ingrediente);
                        div.setAttribute("class","ingre");
                        div.innerHTML="<p>"+ingrediente+"</p> <input class='enviar' type='button' value='Eliminar' onclick=\"eliminarIngrediente('"+ingrediente+"')\"><input class='oculto' type='text' name='ingredientes[]' value='"+ingrediente+"'>";
                        ingredientes.appendChild(div);
                        document.getElementById("ingrediente").value="";
                    }

                }
                function eliminarIngrediente(ingrediente)
                {
                    var ingrediente = document.getElementById(ingrediente);
                    ingredientes.removeChild(ingrediente);
                }
            </script>
            </div>

            <div id="agregar" class="campoB">
            <div>
                <input id="ingrediente" class="texto" type="text">
                <input class="enviar" type="button" value="Agregar" onclick="agregarIngrediente()">
            </div>
            </div>

            <h3 class="h">Tipo de comida</h3>
        <?php 
            $qry="select * from tipos";
            $res=mysqli_query($conn,$qry);

    if(mysqli_num_rows($res)>0)
    {
        while($tipo=mysqli_fetch_array($res))
        {
            $qryTipo = "select * from tipoproducto where idProducto='".$_GET["idP"]."' and tipoProducto='".$tipo["tipo"]."'";
            $consulta = mysqli_query($conn,$qryTipo);
            if(mysqli_num_rows($consulta)>0)
                echo "<label class='campoC'> <input type='checkbox' checked name='".$tipo["tipo"]."'>".$tipo["tipo"]."</label>";
            else
                echo "<label class='campoC'> <input type='checkbox' name='".$tipo["tipo"]."'>".$tipo["tipo"]."</label>";
        }
    }
    else
            echo "<p class='p'>Aun no hay tipos de comida</p>";

            $qry="select * from etiquetas";
            $res=mysqli_query($conn,$qry);
?>
            <h3 class='h'>Etiquetas</h3>
<?php
    if(mysqli_num_rows($res)>0)
    {
        while($etiq=mysqli_fetch_array($res))
        {
            $qryEtiq = "select * from etiquetaproducto where idProducto='".$_GET["idP"]."' and etiquetaProducto='".$etiq["etiqueta"]."'";
            $consulta = mysqli_query($conn,$qryEtiq);
            if(mysqli_num_rows($consulta)>0)
                echo "<label class='campoC'> <input type='checkbox' checked name='".$etiq["etiqueta"]."'>".$etiq["etiqueta"]."</label>";
            else
                echo "<label class='campoC'> <input type='checkbox' name='".$etiq["etiqueta"]."'>".$etiq["etiqueta"]."</label>";
        }
    }
    else
        echo "<p class='p'>Aun no hay etiquetas</p>";

    if($msg!="")
    {
        echo $msg;
    }
        echo"<input name='idP' type='hidden' value='".$_GET["idP"]."'>";
        ?>

            <br>
            <input class='enviar' type='submit' value='Guardar'>
        </form>
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>   
</body>
</html>