/*function mostrarContra(idBoton,idInput)
{
    alert("entro");
    boton = document.getElementById(idBoton);
    input = document.getElementById(idInput);
    if(input.type == "password")
    {
        input.type="text";
    }
    else
    {
        input.type="password";
    }
}*/
function verificaReenvio()
{
    if(window.history.replaceState)
    {
        window.history.replaceState(null,null,window.location.href);
    }
}

var nombreForm;
var nombreDiv
function confirmacion(nomForm)
{
    nombreDiv="confirmacionEnvio";
    var div = document.getElementById(nombreDiv);
    div.style.display = "flex";
    div.style.top = "50%";
    nombreForm=nomForm;
}

function confSi()
{
    var form = document.getElementById(nombreForm);
    if(!valida())
    {    
        confNo();
        return;
    }
    form.submit();
}


function confNo()
{
    var div = document.getElementById(nombreDiv);
    div.style.display = "none";
}