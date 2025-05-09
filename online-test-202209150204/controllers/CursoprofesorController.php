<?php

namespace app\controllers;

use Yii;
use app\models\Cursoprofesor;
use app\models\CursoprofesorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * CursoprofesorController implements the CRUD actions for Cursoprofesor model.
 */
class CursoprofesorController extends Controller
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
     * Lists all Cursoprofesor models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $searchModel = new CursoprofesorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cursoprofesor model.
     * @param integer $curso_id
     * @param string $usuario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($curso_id, $email)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        return $this->render('view', [
            'model' => $this->findModel($curso_id, $email),
        ]);
    }

    /**
     * Creates a new Cursoprofesor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = new Cursoprofesor();

		$tcurso=Yii::$app->db->createCommand('SELECT curso_id, titulo FROM curso')->queryAll();
        $tcursolist=ArrayHelper::map($tcurso,'curso_id', 'titulo');		
        
		$tprof=Yii::$app->db->createCommand('SELECT email, email FROM usuario')->queryAll();
		$tproflist=ArrayHelper::map($tprof,'email', 'email');		        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'curso_id' => $model->curso_id, 'email' => $model->email]);
        }

        return $this->render('create', [
            'model' => $model,
            'tproflist' => $tproflist,
            'tcursolist' => $tcursolist,
        ]);
    }

    /**
     * Updates an existing Cursoprofesor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $curso_id
     * @param string $usuario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($curso_id, $email)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = $this->findModel($curso_id, $email);


		$tcurso=Yii::$app->db->createCommand('SELECT curso_id, titulo FROM curso')->queryAll();
        $tcursolist=ArrayHelper::map($tcurso,'curso_id', 'titulo');		
        
		$tprof=Yii::$app->db->createCommand('SELECT email, email FROM usuario')->queryAll();
		$tproflist=ArrayHelper::map($tprof,'email', 'email');		         

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'curso_id' => $model->curso_id, 'email' => $model->email]);
        }

        return $this->render('update', [
            'model' => $model,
            'tproflist' => $tproflist,
            'tcursolist' => $tcursolist,

        ]);
    }

    /**
     * Deletes an existing Cursoprofesor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $curso_id
     * @param string $usuario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($curso_id, $email)
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $this->findModel($curso_id, $email)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cursoprofesor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $curso_id
     * @param string $usuario_id
     * @return Cursoprofesor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($curso_id, $email)
    {
        if (($model = Cursoprofesor::findOne(['curso_id' => $curso_id, 'email' => $email])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
