<?php

namespace app\controllers;

use Yii;
use app\models\Usuario;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
define("ADMINS",["DIRECCION GENERAL","MESA DE CONTROL"]); 
class UsuarioController extends Controller
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
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		if(!isset($_SESSION["usuario"])||(!in_array($_SESSION["perfil"],constant("ADMINS")))){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
        $searchModel = new UsuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionLogin()
    {
		
		if (Yii::$app->request->post()) {
			$session = Yii::$app->session;
			
			$nomuser= Yii::$app->request->post()["nomuser"];
			$pass= Yii::$app->request->post()["pass"];
			$u=Usuario::Find()->where(['email' => $nomuser,'password' => $pass,'estatus' => 'ACTIVO'])->one();
			if ($u!=null) {
				$session->set('usuario', $u->email);
				$session->set('perfil', $u->perfil);
				return Yii::$app->response->redirect(['descarga/index']);
			}else{
				return $this->render('login',['error'=>'Usuario o password incorrectos.']);
			}
		}
		
        return $this->render('login',['error'=>'']);
    }
	
	
	public function actionChangepass()
    {
		
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}		
		
		if (Yii::$app->request->post()) {
			$session = Yii::$app->session;
			
			$pass= Yii::$app->request->post()["password"];
			$u=Usuario::Find()->where(['email' => $_SESSION["usuario"]])->one();
			if ($u!=null) {
				$u->password=$pass;
				$u->save();
				return $this->render('changepass',['correcto'=>'true']);
			}else{
				return Yii::$app->response->redirect(['usuario/login']);
			}
		}
		
        return $this->render('changepass',['error'=>'','correcto'=>'false']);
    }	
	
	public function actionLogout()
    {
        return $this->render('logout');
    }	

    /**
     * Displays a single Usuario model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
		if(!isset($_SESSION["usuario"])||(!in_array($_SESSION["perfil"],constant("ADMINS")))){
			return Yii::$app->response->redirect(['usuario/login']);
		}		
		
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
		if(!isset($_SESSION["usuario"])||(!in_array($_SESSION["perfil"],constant("ADMINS")))){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->email]);
        }

        return $this->render('create', [
            'model' => $model,
			'keyconst' => false,
        ]);
    }

    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		
		if(!isset($_SESSION["usuario"])||(!in_array($_SESSION["perfil"],constant("ADMINS")))){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->email]);
        }

        return $this->render('update', [
            'model' => $model,
			'keyconst' => true,
        ]);
    }

    /**
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
