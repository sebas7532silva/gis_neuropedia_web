<?php

namespace app\controllers;

use Yii;
use app\models\Codigodescuento;
use app\models\CodigodescuentoSearch;
use app\models\Codigodescuentolog;
use app\models\CodigodescuentologSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CodigodescuentoController implements the CRUD actions for Codigodescuento model.
 */
class CodigodescuentoController extends Controller
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
     * Lists all Codigodescuento models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $searchModel = new CodigodescuentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Codigodescuento model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $searchModel = new CodigodescuentologSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere('codigodescuento_id = '.$id);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $this->findModel($id),
        ]);
		
    }

    /**
     * Creates a new Codigodescuento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        $model = new Codigodescuento();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Codigodescuento model.
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
     * Deletes an existing Codigodescuento model.
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
     * Finds the Codigodescuento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Codigodescuento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}				
		
        if (($model = Codigodescuento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
