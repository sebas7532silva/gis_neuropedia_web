<style>
    .button:hover{
        background-color:#02adc6;
    }
    .button{
        padding-left:5em;
        padding-right:5em;
        padding-top:1em;
        padding-bottom:1em;
        background-color:#434a5a;
        color:white;
        border:0;
        font-weight:700;
    }
</style>

<script>

window.parent.scroll(0,0);


function runval(){

var nombre=document.getElementById("nombre").value;
var email=document.getElementById("email").value;
var telefono=document.getElementById("telefono").value; 

    if(nombre!=""&&email!=""){
        if(true){
            if(true){
                if(telefono==""){
                    return true;
                }else if(telefono!=""&&validaTelefono(telefono)){
                    return true;
                }else{
                    mensaje="Teléfono es inválido.";
                }
            }else{
                mensaje="E-mail es inválido.";
            }
        }else{
            mensaje="Nombre y Apellido inválidos.";
        }
    }else{
        mensaje="Nombre, Apellido e Email son obligatorios.";
    }
    document.getElementById("errorm").innerHTML=mensaje;
    return false;
}

function validaNombre(nombre){
    var regName = /^[a-zA-Z\u00C0-\u024F\u1E00-\u1EFF]+ [a-zA-Z\u00C0-\u024F\u1E00-\u1EFF]+$/;
    if(!regName.test(nombre)){
        return false;
    }else{
        return true;
    }
}

function validaEmail(email){
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validaTelefono(telefono){
    var numbers = /^[0-9]+$/;
      if(telefono.match(numbers))
      {
          return true;
      }else{
          return false;
      }
}

function validarSub(){
    event.stopPropagation();
    return runval();
}

</script>

<?php

if(isset($_POST["nombre"])){
    $mysqli = new mysqli('localhost', 'daminssc_dragisneuropediawp', 'dr4g1sn3ur0pedi4202008-038*', 'daminssc_dragiscrm');
    $sql="INSERT INTO descarga (nombreapellido, email, telefono, ip, fecha, data) VALUES ('".$_POST["nombre"]."', '".$_POST["email"]."', '".$_POST["telefono"]."', '".$_SERVER['REMOTE_ADDR']."', now(), '".$_REQUEST."')";
    $mysqli->query($sql);
    $mysqli->close();

    $mysqli = new mysqli('localhost', 'daminssc_dragisneuropediawp', 'dr4g1sn3ur0pedi4202008-038*', 'daminssc_dragiscrm');
    $sql="SELECT nombre, archivo FROM recurso ORDER BY id DESC limit 1;";
    $result=$mysqli->query($sql);
    $result=mysqli_fetch_array($result);
    $mysqli->close();


?>
    <div style="position: float; float:left"><a href="https://dragisneuropedia.com/crm/web/files/<?=$result ["archivo"]?>" target="_blank"><img src="https://dragisneuropedia.com/crm/web/images/pdf.png" style="height:8em"></a></div>
    <div style="position: float;float:left;margin-left: 2em;font-family: arial;font-size: 1.5em;margin-top: 2em;"><a href="https://dragisneuropedia.com/crm/web/files/<?=$result ["archivo"]?>" target="_blank"><?=$result ["nombre"]?> (Descarga)</a></div>

<?php
}else{

?>

<div class="wpb_wrapper">        
    <form name="contact_form" id="contact_form" class="contact-form" method="post" action="<?php $_SERVER["PHP_SELF"]; ?>" novalidate="novalidate">
        <div class="col-md-4">
        <div style="margin-bottom:2em">
            <img style="height:auto; max-width:75%" src="crm/web/images/ads_gis.jpg">
        </div>
        <div style="Font-family:arial; margin-bottom: 2em">
            En este manual “primeros indicios” podrás encontrar información útil, fácil de entender y sumamente valiosa sobre cómo detectar datos de alarma sobre algunos de los trastornos generalizados del desarrollo más comunes, además encontrarás recomendaciones sobre qué hacer en caso de que detectes algunos de ellos en tus pequeñxs y cuando es necesario acudir con un especialista (neurólogo pediatra) <br/><br/>
            <strong>Si sospechas que tu pequeñx podría tener alguno de estos padecimientos o notas datos de alarma y necesitas ayuda, no esperes más tiempo y contáctame</strong>
        </div>
        <input type="text" data-delay="300" class="required" placeholder="Nombre y Apellido*" name="nombre"  id="nombre" aria-required="true" style="padding-top: 1em; padding-bottom: 1em; text-indent: 10px; border: 1px solid #aaaaaa; font-size: 1em;margin-bottom:1em; width: 20em"> 
        <input type="text" data-delay="300" class="required" placeholder="E-mail*" name="email" id="email" aria-required="true" style="padding-top: 1em; padding-bottom: 1em; text-indent: 10px; border: 1px solid #aaaaaa; font-size: 1em; margin-bottom:1em; margin-bottom:1em; width: 15em">      
        <br/>       
        <input type="text" data-delay="300" placeholder="Celular (Opcional)" name="telefono" id="telefono" aria-required="true" style="padding-top: 1em; padding-bottom: 1em; text-indent: 10px; border: 1px solid #aaaaaa; font-size: 1em; margin-bottom:1em; width: 15em">       
        </div>
        <div class="row">
            <div class="col-md-3">
            <div id="errorm" style="color:red;margin-top: 1em;margin-bottom: 1em;font-family:arial"></div><br/>
                <input name="submit" id="submit-button" type="submit" value="DESCARGAR" class="button" onclick="return validarSub();">
            </div>
        </div>
    </form>
</div>  

<?php
}

?>