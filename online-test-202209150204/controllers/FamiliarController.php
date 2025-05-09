<?php

namespace app\controllers;

use Yii;
use app\models\Familiar;
use app\models\Actividadfamiliarhistorico;
use app\models\Preguntausuariorespuesta;
use app\models\Famililarhistorico;
use app\models\Edad;
use app\models\FamiliarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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
		
		
		$fdata = Familiar::find()->with("famililarhistoricos")->where('usuario_id=:uid', array(':uid'=>$_SESSION["usuario"]))->all();
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
