<?php

namespace app\controllers;

use Yii;
use app\models\Actividadfamiliarhistorico;
use app\models\ActividadfamiliarhistoricoSearch;
use app\models\Actividad;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Familiar;
require_once "../models/Funciones.php";

/**
 * ActividadfamiliarhistoricoController implements the CRUD actions for Actividadfamiliarhistorico model.
 */
class ActividadfamiliarhistoricoController extends Controller
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
     * Lists all Actividadfamiliarhistorico models.
     * @return mixed
     */
    public function actionIndex()
    {
		$familiarid=$_GET["familiarid"];
        $familiar = Familiar::find()->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid])->one();
		$actividad = Actividad::find()->where(['actividad_id' => $_GET["actividadid"] ])->one();
		$historicos = Actividadfamiliarhistorico::find()->where(['actividad_id' => $_GET["actividadid"], "familiar_id"=>$familiarid ])->orderBy("fecha desc")->all();

		$ahsn = Actividadfamiliarhistorico::find()->where(['actividad_id' => $_GET["actividadid"], "familiar_id"=>$familiarid ])->count();
				
		$edadm=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);

        return $this->render('index', [
            'familiar' => $familiar,
            'edadm' => $edadm,
            'familiarid' => $familiarid,
            'actividad' => $actividad,
            'historicos' => $historicos,
            'ahsn' => $ahsn,
        ]);
    }

    /**
     * Displays a single Actividadfamiliarhistorico model.
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
     * Creates a new Actividadfamiliarhistorico model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Actividadfamiliarhistorico();
		
		$familiarid=$_GET["familiarid"];
        $familiar = Familiar::find()->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid])->one();
		$actividad = Actividad::find()->where(['actividad_id' => $_GET["actividadid"] ])->one();
		$ahs = Actividadfamiliarhistorico::find()->where(['actividad_id' => $_GET["actividadid"], "familiar_id"=>$familiarid ])->orderBy("fecha desc")->one();
		$ahsn = Actividadfamiliarhistorico::find()->where(['actividad_id' => $_GET["actividadid"], "familiar_id"=>$familiarid ])->count();
				
		$edadm=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);
		
	
        if ($model->load(Yii::$app->request->post())) {
			
			$model["familiar_id"]=$familiarid;
			$model["actividad_id"]=$_GET["actividadid"];
			$model->save();
            return $this->redirect(['actividad/index', 'familiarid' => $familiarid]);
        }

        return $this->render('create', [
            'model' => $model,
            'familiar' => $familiar,
            'edadm' => $edadm,
            'familiarid' => $familiarid,
            'actividad' => $actividad,
            'ahs' => $ahs,
            'ahsn' => $ahsn,
        ]);
    }

    /**
     * Updates an existing Actividadfamiliarhistorico model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->actividadhistorico_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Actividadfamiliarhistorico model.
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
     * Finds the Actividadfamiliarhistorico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actividadfamiliarhistorico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Actividadfamiliarhistorico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
