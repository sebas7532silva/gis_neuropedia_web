<?php


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
