<?php

namespace app\controllers;

use Yii;
use app\models\Recurso;
use app\models\RecursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RecursoController implements the CRUD actions for Recurso model.
 */
class RecursoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;


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
     * Lists all Recurso models.
     * @return mixed
     */
    public function actionIndex()
    {

		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}		

        $searchModel = new RecursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Recurso model.
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
     * Creates a new Recurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}		

        $model = new Recurso();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->archivox = UploadedFile::getInstance($model, 'archivox');
            $model->archivo=$model->archivox->basename."-".str_replace(".","",str_replace(" ","",microtime()."")).".". $model->archivox->extension;

            if ($model->upload()) {
                $model->archivox=null;
                $model->save();
                return $this->redirect(['index']);
                //return $this->redirect(['site/home']);
            }else{
                echo "error";die();
            }

        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Recurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Recurso model.
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
     * Finds the Recurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recurso::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
