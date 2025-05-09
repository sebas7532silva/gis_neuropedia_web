<?php

namespace app\controllers;

use Yii;
use app\models\Curso;
use app\models\Modulo;
use app\models\ModuloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * ModuloController implements the CRUD actions for Modulo model.
 */
class ModuloController extends Controller
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
     * Lists all Modulo models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $searchModel = new ModuloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $curso = Curso::find()->where(['curso_id' => Yii::$app->request->get('curso_id')])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'curso' => $curso,
        ]);
    }

    /**
     * Displays a single Modulo model.
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
     * Creates a new Modulo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = new Modulo();

		$tprof=Yii::$app->db->createCommand('SELECT email, email FROM usuario')->queryAll();
		$tproflist=ArrayHelper::map($tprof,'email', 'email');		     

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->modulo_id, 'curso_id' => Yii::$app->request->get('curso_id')]);
        }

        return $this->render('create', [
            'model' => $model,
            'tproflist' => $tproflist,
        ]);
    }

    /**
     * Updates an existing Modulo model.
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

		$tprof=Yii::$app->db->createCommand('SELECT email, email FROM usuario')->queryAll();
		$tproflist=ArrayHelper::map($tprof,'email', 'email');		     

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->modulo_id, 'curso_id' => $model->modulo_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'tproflist' => $tproflist,
        ]);
    }

    /**
     * Deletes an existing Modulo model.
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
     * Finds the Modulo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Modulo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Modulo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
