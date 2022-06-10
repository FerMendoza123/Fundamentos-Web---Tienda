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
            $nombre = $_FILES['imagen']['name'];
            $tipo = $_FILES['imagen']['type'];

            $nombreTemporal = $_FILES['imagen']['tmp_name'];
            $tamanio = $_FILES['imagen']['size'];

            $nombre = $_POST['nombre'];
            $precio = $_POST["precio"];
            $descripcion = $_POST['descripcion'];

            $fp = fopen($nombreTemporal,'r');
            $contenido = fread($fp,$tamanio);
            fclose($fp);

            $contenido = addslashes($contenido);

            $qry = "insert into productos (nombre, precio, descripcion, imagen, tipo) 
                values('$nombre','$precio','$descripcion','$contenido','$tipo')";
            
            
            if(mysqli_query($conn,$qry))
            {
                $qry = "select last_insert_id() as idP";
                $res = mysqli_query($conn,$qry);
                $id = mysqli_fetch_array($res);
                $id = $id["idP"];
                if(isset($_POST["ingredientes"]))
                {
                    $ingredientes = $_POST["ingredientes"];
                    foreach($ingredientes as $ingrediente)
                    {
                        $qry="insert into ingredientes (idProducto, ingrediente)
                                values ('$id','$ingrediente')";
                        mysqli_query($conn,$qry);
                    }
                }
                
                $qryTipos = "select * from tipos";
                $qryEtiquetas = "select * from etiquetas";

                
                
                $resT = mysqli_query($conn,$qryTipos);
                $resE = mysqli_query($conn,$qryEtiquetas);

                if(mysqli_num_rows($resT)>0)
                {
                    while($reg = mysqli_fetch_array($resT))
                    {
                        if(isset($_POST[$reg["tipo"]]))
                        {
                            $qryTipos = "insert into tipoproducto (idProducto, tipoProducto) 
                                        values ('$id','".$reg["tipo"]."')";
                            mysqli_query($conn,$qryTipos);
                        }
                    }
                }
                
                if(mysqli_num_rows($resE)>0)
                {
                    while($reg = mysqli_fetch_array($resE))
                    {
                        if(isset($_POST[$reg["etiqueta"]]))
                        {
                            $qryEtiquetas = "insert into etiquetaproducto (idProducto, etiquetaProducto) 
                                        values ('$id','".$reg["etiqueta"]."')";
                            mysqli_query($conn,$qryEtiquetas);
                        }
                    }
                }
                $msg = "<p class='pCorrecto'>El producto fue dado de alta con exito</p>";
            }
            else
                $msg = "<p class='pError'>Ocurrio un error y no se pudo realizar el registro</p>";
        }
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
    </style>
    <?php
        relacionesFormulario();
        relacionesEstiloBarra();
    ?>
</head>

<body>
<?php
    encabezado();
?>
<section class="contenidoPagina">
        <h2>Agregar Producto</h2>
        <hr>
        <br>
        <br>
        <form class="formulario" action="agregarProducto.php" method="post" enctype="multipart/form-data">
            <label class='campo'><p>*Nombre:</p> <input type="text" id="nombre" name="nombre"></label>
            <label class='campo'><p>*Precio(pesos):</p> <input type="number" id="precio" name="precio"></label>
            <label class='campo'><p>Descripción:</p> <input type="text" id="descripcion" name="descripcion"></label> 
            <label class='campo'><p>*Imagen:</p> <input type="file" id="imagen" name="imagen"></label>
            <h3 class="h">*Ingrediente/Sabor</h3>

            <div id="ingredientes">
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
        while($reg=mysqli_fetch_array($res))
        {
            echo "<label class='campoC'> <input type='checkbox' name='".$reg["tipo"]."'>".$reg["tipo"]."</label>";
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
        while($reg=mysqli_fetch_array($res))
        {
            echo "<label class='campoC'> <input type='checkbox' name='".$reg["etiqueta"]."'>".$reg["etiqueta"]."</label>";
        }
    }
    else
        echo "<p class='p'>Aun no hay etiquetas</p>";

    if($msg!="")
    {
        echo $msg;
    }
        ?>
            <br>
            <input class='enviar' type='submit' value='Agregar'>
        </form>
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>   
    <?php
        footer();
    ?>
</body>
</html>