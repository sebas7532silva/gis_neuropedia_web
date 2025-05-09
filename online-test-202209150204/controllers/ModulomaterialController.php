<?php

namespace app\controllers;

use Yii;
use app\models\Modulomaterial;
use app\models\ModulomaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;


/**
 * ModulomaterialController implements the CRUD actions for Modulomaterial model.
 */
class ModulomaterialController extends Controller
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
     * Lists all Modulomaterial models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $searchModel = new ModulomaterialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Modulomaterial model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Modulomaterial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = new Modulomaterial();

        
		$tm=Yii::$app->db->createCommand('SELECT modulo_id, titulo FROM modulo')->queryAll();
        $tml=ArrayHelper::map($tm,'modulo_id', 'titulo');		

        if ($model->load(Yii::$app->request->post())) {
            
            $model->archivo = UploadedFile::getInstance($model, 'archivo');
            $model->filename=microtime().".". $model->archivo->extension;
            if ($model->upload()) {
                $model->archivo=null;
                $model->save();
                return $this->redirect(['view', 'id' => $model->modulo_id]);
                //return $this->redirect(['site/home']);
            }

        }

        return $this->render('create', [
            'model' => $model,
            'tml' => $tml,
        ]);
    }

    /**
     * Updates an existing Modulomaterial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->modulo_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Modulomaterial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Modulomaterial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Modulomaterial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Modulomaterial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
