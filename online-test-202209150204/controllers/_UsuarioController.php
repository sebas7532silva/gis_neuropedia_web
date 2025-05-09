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
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $searchModel = new UsuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Displays a single Familiar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTerms()
    {
        return $this->render('terms', [
            
        ]);
    }
	
    public function actionHelp()
    {
        return $this->render('help', [
            
        ]);
    }
	

    /**
     * Displays a single Familiar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRecover()
    {
		
		if(isset($_SESSION["usuario"])){
			$_SESSION = array();
			
			// Si se desea destruir la sesi�n completamente, borre tambi�n la cookie de sesi�n.
			// Nota: �Esto destruir� la sesi�n, y no la informaci�n de la sesi�n!
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			
			// Finalmente, destruir la sesi�n.
			session_unset();
			session_destroy();
		}
		
		$message="";
		$model = new Usuario();
        if (Yii::$app->request->post()) {
			$model=Usuario::Find()->where(["email"=>Yii::$app->request->post()["Usuario"]["email"]])->one();
			$rand = substr(md5(microtime()),rand(0,26),7);
			$model->password=$rand;	
			$model->save();
			try{
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';
				$headers[] = "From: <citas@dragisneuropedia.com>";
				error_reporting(~E_WARNING);				
				mail($model->email,"Password Temporal Neurobaby", "Tu password temporal es: ".$model["password"].", favor de cambiarlo al ingresar.", implode("\r\n", $headers)); 
			}catch(Exception $e) {
				
			}
			$message="Favor de Revisar tu Email para Continuar.";
        }

		
        return $this->render('recuperarContrasena', [
            'message' => $message,
            'model' => $model,
        ]);
		
		
    }
	

    /**
     * Displays a single Usuario model.
     * @param string $id
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
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = new Usuario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->email]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreatecliente()
    {
		if(Yii::$app->session->id!=null){		
			// Destruir todas las variables de sesi�n.
			$_SESSION = array();
			
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			
			session_unset();
			session_destroy();
		}		
		
        $model = new Usuario();
        $model->load(Yii::$app->request->post());
        $model->curriculo="";
        $model->perfil="CLIENTE";
        $model->estatus="ACTIVO";
		$model->horasdisponibles="0";
		$session = Yii::$app->session;

        if ($model->save()) {
			
			$u=Usuario::Find()->where(['email' => $model->email])->one();
			if ($u!=null) {
				$session->set('usuario', $u->email);
                $session->set('perfil', $u->perfil);
                $session->set('horasdisponibles', $u->horasdisponibles);
                if($u->perfil=="CLIENTE"){
                    return Yii::$app->response->redirect(['familiar/index']);
                }else{
                    return Yii::$app->response->redirect(['site/home']);
                }
			}			
			
            return $this->redirect(['usuario/login']);
        }

        return $this->render('createcliente', [
            'model' => $model,
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
        if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="PROFESOR"){
			return Yii::$app->response->redirect(['usuario/login']);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->email]);
        }

        return $this->render('update', [
            'model' => $model,
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
                $session->set('horasdisponibles', $u->horasdisponibles);
                if($u->perfil=="CLIENTE"){
                    return Yii::$app->response->redirect(['familiar/index']);
                }else{
                    return Yii::$app->response->redirect(['site/home']);
                }
			}else{
				return $this->render('login',['error'=>'Email o password incorrectos.']);
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
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
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

