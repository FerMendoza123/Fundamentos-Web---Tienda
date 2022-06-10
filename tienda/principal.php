<?php
    session_start();
    include("funciones.php");
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
    ?>
    <style>
        #slider
        {
            width:100%;
            height:620px;
        }
        #slides
        {
            width:100%;
            height:600px;
        }
        .slide
        {
            width:100%;
            height:600px;
            background-position:center;
            background-size:cover;
            background-repeat:no-repeat;
            opacity: 0%;
            position:absolute;
        }
        #slide1{
            opacity: 100%;
        }

       
    </style>

    <script type="text/javascript">
        function pagina()
        {
            location.href=document.getElementById("link"+slideActual).href;
        }
    </script>
</head>

<body onload="cargaSlider()">
    <?php
        encabezado();
    ?>

    
    <?php
        $qry = "select p.nombre, pr.descuento, pr.idProducto from promociones as pr inner join productos as p where pr.slider = '1' and p.idProducto=pr.idProducto";
        $conn = conectarBD();

        $promos = mysqli_query($conn,$qry);

        if(mysqli_num_rows($promos)>0)
        {
    echo
    "<div id='slider'>
        <div id='slides' onclick='pagina()'>";
            $nSlide=1;
            while($promo = mysqli_fetch_array($promos))
            {
            echo"<div style=\"background-image: url('imagenProducto.php?idIp=".$promo["idProducto"]."');\" class='slide' id='slide$nSlide'>
                    <div style='color: white; font-size:70%; border: 1px solid white; background-color:black;border-radius:30px;width:fit-content;padding:20px;margin-top:20%'>
                    <h1>".$promo["nombre"]."</h1>
                    <h2>Ahora con ".$promo["descuento"]."% de descuento</h2>
                    </div>
                    <a id='link$nSlide' href='producto.php?idP=".$promo["idProducto"]."'></a>
                </div>";
                $nSlide+=1; 
            } 
    echo"</div>
        <nav id='menuSlider'>
        </nav>
    </div>";
    ?>

        <script>

            var slideActual=1;
            var tiempo=3700;

            function cargaSlider()
            {
                var slides = document.getElementById("slides").children.length;
                var s = "";
                s += "<a href=\"#\" id=\"idSlidePrevio\" onclick=\"javascript:cambiaSlide('previo')\"><</div>";
                for(i=0;i<slides;i++)
                    s += "<a href=\"#\" id=\"idSlide"+(i+1)+"\" onclick=\"javascript:cambiaSlide('"+(i+1)+"')\">"+(i+1)+"</div>";
                s += "<a href=\"#\" id=\"idSlidePosterior\" onclick=\"javascript:cambiaSlide('posterior')\">></div>";
                document.getElementById("menuSlider").innerHTML = s;
                
                document.getElementById("slide" + slideActual).style.opacity=1;
            }
            setInterval(
                function()
                {
                    let slideN=parseInt(slideActual)+1;
                //alert(slideActual +" ad "+slideN);
                    if(slideN==<?php echo $nSlide;?>)
                        slideN=1;
                    cambiaSlide(slideN);
                }
            ,tiempo);

            function cambiaSlide(s)
            {
                if(isNaN(s))
                {
                    //if(!(s<"previo")&&!(s>"previo"))
                    if(s=="previo")
                    {
                        s=slideActual-1;
                    }
                    else
                    {
                        s=slideActual+1;	
                    }
                    if(s>2)
                        s=1;
                    if(s<1)
                        s=2;
                }
                if(s!=slideActual)
                {
                    var actual=document.getElementById("slide" + slideActual);
                    var nuevo=document.getElementById("slide" + s);
                    var opActual= 1;
                    var opNuevo= 0;
                    var i=1;
                    var intervalo = setInterval(
                        function()
                        {
                            if(i<=10)
                            {
                                opActual-=.1
                                actual.style.opacity=opActual;
                            }
                            else
                            {
                                if(i>17)
                                {
                                    clearInterval(intervalo);
                                }
                            }
                            if(i>5)
                            {
                                opNuevo+=.1;
                                nuevo.style.opacity=opNuevo;
                            }
                            i++;
                        }	
                    ,50);
                    slideActual=s;
                }
            }
        </script>
        <?php
        }
        else
        {
        ?>
        <script>
            function cargaSlider()
            {
                document.getElementById("difuminado").opacity="100%";
                document.getElementById("difuminado").position="relative";
            }
        </script>
        <?php
        }
        footer();
    ?>
</body>
</html>