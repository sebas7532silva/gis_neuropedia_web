<?php

namespace app\controllers;

use Yii;
use app\models\Curso;
use app\models\CursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * CursoController implements the CRUD actions for Curso model.
 */
class CursoController extends Controller
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
     * Lists all Curso models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }
                
        $searchModel = new CursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Curso model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $ptits=Yii::$app->db->createCommand('SELECT concat(p.nombre," ",p.apellido," (",cp.titularidad,")") as profesor, p.email, cp.titularidad FROM cursoprofesor cp, usuario p WHERE cp.email=p.email AND cp.curso_id='.$id.';')->queryAll();
        $ptitsl=ArrayHelper::map($ptits,'email', 'profesor');		

        return $this->render('view', [
            'model' => $this->findModel($id),
            'ptitsl' => $ptitsl,
        ]);
    }

    /**
     * Creates a new Curso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = new Curso();

        $tc=Yii::$app->db->createCommand('SELECT tipo_id, nombre FROM tipo_curso')->queryAll();
		$tcl=ArrayHelper::map($tc,'tipo_id', 'nombre');		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->curso_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'tcl' => $tcl,
        ]);
    }

    /**
     * Updates an existing Curso model.
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

		$tc=Yii::$app->db->createCommand('SELECT tipo_id, nombre FROM tipo_curso')->queryAll();
		$tcl=ArrayHelper::map($tc,'tipo_id', 'nombre');		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->curso_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'tcl' => $tcl,
        ]);
    }

    /**
     * Deletes an existing Curso model.
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
     * Finds the Curso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Curso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Curso::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
