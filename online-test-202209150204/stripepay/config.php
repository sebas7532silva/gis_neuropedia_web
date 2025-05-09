<?php

	namespace app\controllers;



	use Yii;

	use app\models\Producto;



    require_once "../stripe8110/init.php";



	$nblogin = Producto::find()->where('producto=:prd', array(':prd'=>"nblogin"))->one();

	$nbextra1 = Producto::find()->where('producto=:prd', array(':prd'=>"nbextra1"))->one();





    $stripeDetails = array(

        "secretKey" => "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",

        "publishableKey" => "pk_live_51LNs71CxeVrpAeWHGXe9ZH8bLn4PUfHKRGUHAbs5OpWkSJUJ40OBHw602Q2udbEI6y1bd58q3lY4kgvDAwQS86Xn00PBo6DvEC",

		"precioLoginNeurobaby" => $nblogin["precio"],

		"precioExtra1Neurobaby" => $nbextra1["precio"]

    );



    \Stripe\Stripe::setApiKey($stripeDetails["secretKey"]);



   

?>