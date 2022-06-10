var op;

function mostrarSM(sm, opcion)
{
    var item = document.getElementById(opcion);
    var sMenu = document.getElementById(sm);
    sMenu.style.visibility = "visible";
    
    op=item;
    item.style.color="gray";
    var h = item.offsetHeight;
    var l = item.offsetLeft;
    var t = item.offsetTop;
    
    sMenu.style.top = (t + h) + "px"
    sMenu.style.left = (l - sMenu.offsetWidth + item.offsetWidth*2) + "px";

}

function ocultaSM(sm)
{
    op.style.color="white";
    var sMenu = document.getElementById(sm);
    sMenu.style.visibility = "hidden";
}

function validaDatos()
{
    var busqueda = document.getElementById("buscar").value;
    if(busqueda=="")
    {
        return false;
    }
}