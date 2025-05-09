<?php

namespace app\controllers;

use Yii;
use app\models\Codigodescuentousuario;
use app\models\CodigodescuentousuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \Datetime;
use \Datetimezone;
require_once "../models/Funciones.php";

/**
 * CodigodescuentousuarioController implements the CRUD actions for Codigodescuentousuario model.
 */
class CodigodescuentousuarioController extends Controller
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
     * Lists all Codigodescuentousuario models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $searchModel = new CodigodescuentousuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Codigodescuentousuario model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Codigodescuentousuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $model = new Codigodescuentousuario();

        if ($model->load(Yii::$app->request->post())) {
			
			
			$now = new DateTime(null, new DateTimeZone('America/Mexico_City'));
			$model["fechaenvio"]=$now->format('Y-m-d H:i:s');
			$model["estatus"]="ENVIADO";
			if($model["tipo"]=="PRUEBA 2 SEMANAS GRATIS"){
				$model["porcentaje"]=0;
			}
			$model["codigo"]=generaCodigoUsuario($model["usuario"],$model["tipo"],$model["fechaenvio"],$model["porcentaje"],$model["estatus"]);
			$model->save();
							
			$copysubject="";
			$copytitulo="";
			$copybody="";
			$copytextolink="";
			$copylink="";
			if($model["tipo"]=="DESCUENTO"){
				$copysubject="Neurobaby te regala un ".$model["porcentaje"]."% de descuento";				
				$copytitulo=$model["porcentaje"]."% de descuento";
				$copytextolink="Registrarse Ahora";
				$copylink="https://dragisneuropedia.com/online-test/web/index.php?r=usuario/createcliente&codigo=".$model["codigo"];				
				$copybody="Neurobaby te regala un ".$model["porcentaje"]."% de descuento exclusivo para ti, si te registras con este mismo mail.";				
			}else{
				$copysubject="Neurobaby te regala 2 semanas gratis";
				$copytitulo="Prueba Neurobaby gratis durante 2 semanas";
				$copytextolink="Registrarse Ahora";
				$copylink="https://dragisneuropedia.com/online-test/web/index.php?r=usuario/createcliente&codigo=".$model["codigo"];								
				$copybody="Neurobaby te regala 2 semanas de prueba gratuita, exclusivas para ti si te registras con este mail.";				
			}
				
			email($model->usuario,$copysubject,$copytitulo,$copybody,$copytextolink, $copylink,"papa.png","#F5F8FA");
			$model->save();
							
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Codigodescuentousuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Codigodescuentousuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Codigodescuentousuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Codigodescuentousuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        if (($model = Codigodescuentousuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
