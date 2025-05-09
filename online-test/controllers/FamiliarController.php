<?php

namespace app\controllers;

use Yii;
use app\models\Familiar;
use app\models\Actividadfamiliarhistorico;
use app\models\Preguntausuariorespuesta;
use app\models\Famililarhistorico;
use app\models\Edad;
use app\models\Codigodescuentousuario;
use app\models\Codigodescuento;
use app\models\FamiliarSearch;
use app\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \Datetime;
use \Datetimezone;
require_once "../models/Funciones.php";

/**
 * FamiliarController implements the CRUD actions for Familiar model.
 */
class FamiliarController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Familiar models.
     * @return mixed
     */
    public function actionIndex()
    {
		
        if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
        }
		
		if($_SESSION["perfil"]=="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['site/home']);
		}
		
		$freetrial=false;
		$descuento=0;
		
		$fdata = Familiar::find()->with("famililarhistoricos")->where('usuario_id=:uid', array(':uid'=>$_SESSION["usuario"]))->all();
		$usuario = Usuario::find()->where('email=:uid', array(':uid'=>$_SESSION["usuario"]))->one();
		
		if($usuario["codigodescuento"]!=null){
			
			$c=$usuario["codigodescuento"];
			$cdu=Codigodescuentousuario::Find()->where(" codigo='".$c."' ")->one();
			$cd=Codigodescuento::Find()->where(" codigodescuento='".$c."'")->one();
			$now = new DateTime(null, new DateTimeZone('America/Mexico_City'));
			$now=$now->format('Y-m-d H:i:s');					
						
			if($cdu){
				$c=base64_decode($usuario["codigodescuento"]);
				
				$c=explode("|",$c);
				
				$c = Codigodescuentousuario::find()->where("usuario='".$_SESSION["usuario"]."' AND usuario='".$c[0]."' AND tipo='".$c[1]."' AND fechaenvio='".$c[2]."' AND porcentaje=".$c[3]."")->one();
				
				
				if($c["tipo"]=="PRUEBA 2 SEMANAS GRATIS"){
					if($c["fechafin"]==null){
						$c["fechainicio"]=$now;  
						$now = new DateTime(null, new DateTimeZone('America/Mexico_City'));
						$now->modify("+14 days");
						$c["fechafin"] =  $now->format('Y-m-d H:i:s');	
						$c["estatus"]="GASTADO";
						$c->save();
						$freetrial=true;
						$_SESSION["horasdisponibles"]=2;
					}elseif($c["fechafin"]>=$now){
						$freetrial=true;
						$_SESSION["horasdisponibles"]=2;
					}elseif($c["fechafin"]<$now){
						$freetrial=false;
						$_SESSION["horasdisponibles"]=0;
					}						
				}
				
				if($c["tipo"]=="DESCUENTO"&&$c["estatus"]=="ENVIADO"){
					$descuento=$c["porcentaje"];
				}
				
			} elseif ($cd) {
				$c=$usuario["codigodescuento"];
				$c = Codigodescuento::find()->where("codigodescuento='".$c."' AND estatus='ACTIVO'")->one();
				if($c["fechainicio"]<=$now&&$c["fechafin"]>=$now){
					$descuento=$c["porcentaje"];
				}
			}
			
			
		}
		

		$familia=[];
		foreach($fdata as $miembro){
			$fh=$miembro["famililarhistoricos"];
			$afh=Actividadfamiliarhistorico::find()->where('familiar_id=:fid', array(':fid'=>$miembro["familiar_id"]))->orderBy("fecha DESC")->one();
			$pur=Preguntausuariorespuesta::find()->where('familiar_id=:fid', array(':fid'=>$miembro["familiar_id"]))->one();
			
			$edad=Edad::find()->where("edadnumerica>=".calcularEdadMeses($miembro["fechanacimiento"],$miembro["semanasprematuro"]))->orderBy("edadnumerica ASC")->one();
			$nuevox=Famililarhistorico::find()->where("familiar_id=".$miembro["familiar_id"]." AND edad_id=".$edad->edad_id)->all();
			
			$miembro = $miembro->toArray();
			
			
			//Mostrar o no histórico
			$miembro["famililarhistoricos"]=false;
			if($fh){
				$miembro["famililarhistoricos"]=true;
			}
			
			//Tiene examen incompleto
			$miembro["enexamen"]=false;
			if($pur){
				$miembro["enexamen"]=true;
			}

			$miembro["actividad"]="nunca";
			if($afh){
				$miembro["actividad"]=$afh["fecha"];
			}
			
			$miembro["nuevoex"]=false;
			if(!$nuevox){
				$miembro["nuevoex"]=true;
			}
			
			array_push($familia,$miembro);
		}
		

        return $this->render('index', [
            'familia' => $familia,
			'freetrial'=>$freetrial,
			'descuento'=>$descuento,
        ]);
    }

    /**
     * Displays a single Familiar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Creates a new Familiar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Familiar();
		$model->load(Yii::$app->request->post());
		$model["usuario_id"]=$_SESSION["usuario"];
		
        if ($model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Familiar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Familiar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Familiar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Familiar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Familiar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
