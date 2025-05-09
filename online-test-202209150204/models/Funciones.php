<?php

	function generaCodigoUsuario($usuario,$tipo,$fechaenvio,$porcentaje,$estatus)
    {
		
		return base64_encode($usuario."|".$tipo."|".$fechaenvio."|".$porcentaje."|".$estatus);
		
    }
	
	

	function calcularEdadTexto($fecha)
    {
		$bithdayDate=$fecha;
		
		$date = new DateTime($bithdayDate);
		
		$now = new DateTime();
		$interval = $now->diff($date);
		
		$agnos=$interval->y;
		$meses=$interval->format('%m') + ($interval->y * 12);
		
		$labela="a";
		$labelm="m";
		
		if($agnos<=1){
			$labelm="meses";
		}
		
		if($agnos>=1){
			return "".$agnos." ".$labela." / ".$meses." ".$labelm;
		}else{
			return "".$meses." ".$labelm;
		}
    }

	function calcularEdadMeses($fecha,$semanasprematuro)
    {
		$bithdayDate=$fecha;
		
		$date = new DateTime($bithdayDate);
		
		$now = new DateTime();
		$interval = $now->diff($date);

		if($semanasprematuro>0){
			$meses=$interval->format('%m') + ($interval->y * 12)+round((40-$semanasprematuro)/4,0);
		}else{
			$meses=$interval->format('%m') + ($interval->y * 12);
		}

		
		if($meses>60){
			$meses=60;
		}
		
		return $meses;
    }

	function calcularEdadEpocaTexto($fecha,$epoca)
    {
		$bithdayDate=$fecha;
		
		$date = new DateTime($bithdayDate);
		
		$now = new DateTime($epoca);
		$interval = $now->diff($date);
		
		$agnos=$interval->y;
		$meses=$interval->format('%m') + ($interval->y * 12);
		
		$labela="a";
		$labelm="m";
		
		if($agnos<=1){
			$labelm="meses";
		}
		
		if($agnos>=1){
			return "".$agnos." ".$labela." / ".$meses." ".$labelm;
		}else{
			return "".$meses." ".$labelm;
		}
    }

	function calcularDiferenciaConHoy($fecha)
    {
		
		$date = new DateTime($fecha);
		
		$now = new DateTime();
		$interval = $now->diff($date);
		
		$dias=$interval->days;
		
		return $dias;
    }

	function cumpleMes($fecha)
    {
		$bithdayDate=$fecha;
		
		$date = new DateTime($bithdayDate);
		
		$now = new DateTime();
		
		if($now->format("%d")==$date->format("%d")){
			return true;
		}else{
			return false;
		}
    }

	function cumpleAgnos($fecha)
    {
		$bithdayDate=$fecha;
		
		$date = new DateTime($bithdayDate);
		
		$now = new DateTime();
		
		if($now->format("%d")==$date->format("%d")&&$now->format("%m")==$date->format("%m")){
			return true;
		}else{
			return false;
		}
    }
	
	function email($para,$asunto,$titulo,$cuerpo,$textolink,$link,$imagen,$color){

		 $mensaje='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
			<html>
				<head>
					<link rel="stylesheet" type="text/css" hs-webfonts="true" href="https://fonts.googleapis.com/css?family=Lato|Lato:i,b,bi">
					<title>Email template</title>
					<meta property="og:title" content="Email template">
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<style type="text/css">
				   
					  a{ 
						text-decoration: underline;
						color: inherit;
						font-weight: bold;
						color: #253342;
					  }
					  
					  h1 {
						font-size: 56px;
					  }
					  
						h2{
						font-size: 28px;
						font-weight: 900; 
					  }
					  
					  p {
						font-weight: 100;
					  }
					  
					  td {
					vertical-align: top;
					  }
					  
					  #email {
						margin: auto;
						width: 600px;
						background-color: white;
					  }
					  
					  button{
						font: inherit;
						background-color: #FF7A59;
						border: none;
						padding: 10px;
						text-transform: uppercase;
						letter-spacing: 2px;
						font-weight: 900; 
						color: white;
						border-radius: 5px; 
						box-shadow: 3px 3px #d94c53;
					  }
					  
					  .subtle-link {
						font-size: 9px; 
						text-transform:uppercase; 
						letter-spacing: 1px;
						color: #CBD6E2;
					  }
					  
					</style>
				
				</head>
				<body bgcolor="'.$color.'" style="width: 100%; margin: auto 0; padding:0; font-family:Lato, sans-serif; font-size:18px; color:#33475B; word-break:break-word">      
					<div id="email">
						<table role="presentation" width="100%">
							<tr>
								<td bgcolor="#00A4BD" align="center" style="color: white;">
									<img alt="Flower" src="https://dragisneuropedia.com/online-test/web/images/'.$imagen.'" width="400px" align="middle">
									<h1> '.$titulo.' </h1>
								</td>
						</table>
						<table role="presentation" border="0" cellpadding="0" cellspacing="10px" style="padding: 30px 30px 30px 60px;">
							<tr>
								<td>
									<p>
										'.$cuerpo.'
									</p>
									<button> 
										<a href="'.$link.'">'.$textolink.'</a>
									</button>
								</td> 
							</tr>
						</table>
					</div>
				</body>
			</html>';
			
			try{
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';
				$headers[] = "From: <citas@dragisneuropedia.com>";
				error_reporting(~E_WARNING);				
				mail($para,$asunto, $mensaje, implode("\r\n", $headers)); 
			 }catch(Exception $e) {
				
			 }
			
	}
