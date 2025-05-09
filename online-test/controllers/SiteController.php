<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Modulo;
use app\models\Modulocliente;
use app\models\Modulomaterial;
use app\models\Curso;
use app\models\Examen;
use app\models\Familiar;
use app\models\Usuario;
use app\models\Codigodescuentousuario;
use \Datetime;
use \Datetimezone;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionHome()
    {
		if(!isset($_SESSION["usuario"])||$_SESSION["perfil"]!="ADMINISTRADOR"){
			return Yii::$app->response->redirect(['usuario/logout']);
		}		
		
		return $this->render('home');

    }

    public function actionCursogratis()
    {
        $materiales=[];
        $curso = Curso::find()->where(['curso_id' => Yii::$app->request->get('curso_id')])->one();
        $modulos = Modulo::find()->where(['curso_id' => Yii::$app->request->get('curso_id')])->all();
        if(sizeof($modulos)>0){
            $materiales = Modulomaterial::find()->where(['modulo_id' => $modulos[0]->modulo_id])->all();
        }

        return $this->render('cursogratis', [
            'curso' => $curso,
            'modulos' => $modulos,
            'materiales' => $materiales,
        ]);
    }

    public function actionSesion()
    {
        $materiales=[];

        $mc = Modulocliente::find()->where(['modulo' =>$_GET["modulo_id"],"estatus"=>"PAGADO","cliente"=>$_SESSION["usuario"]])->one();

        $curso = $mc->modulo0->curso;
        $modulo = $mc->modulo0;
        $materiales = Modulomaterial::find()->where(['modulo_id' => $modulo->modulo_id])->all();

        return $this->render('sesion', [
            'curso' => $curso,
            'modulo' => $modulo,
            'materiales' => $materiales,
        ]);
    }

    public function actionSesiones()
    {

        if(isset($_GET["modulo_id"])){
            $finder= Modulocliente::find()->where(['modulo' => Yii::$app->request->get('modulo_id'),'cliente' => $_SESSION["usuario"]])->one();
            if($finder){
                $finder->delete();
            }else{
                $mc = new Modulocliente();
                $mc->modulo=$_GET["modulo_id"];
                $mc->cliente=$_SESSION["usuario"];
                $mc->estatus="CARRITO";
                $mc->save();
            }
        }

        $materiales=[];
        $curso = Curso::find()->where(['curso_id' => Yii::$app->request->get('curso_id')])->one();
        $modulos = Modulo::find()->where(['curso_id' => Yii::$app->request->get('curso_id')])->all();
        $moduloscliente = Modulocliente::find()->where(['cliente' => $_SESSION["usuario"]])->all();
        
        return $this->render('sesiones', [
            'curso' => $curso,
            'modulos' => $modulos,
            'moduloscliente' => $moduloscliente,
        ]);
    }

	//en uso Neurobaby
    public function actionCarrito()
    {
		
		
		$fdata = Familiar::find()->with("famililarhistoricos")->where('usuario_id=:uid', array(':uid'=>$_SESSION["usuario"]))->all();
		$u=Usuario::Find()->where(['email' => $_SESSION["usuario"]])->one();
        
		$c = Codigodescuentousuario::find()->where("usuario='".$_SESSION["usuario"]."'")->one();
		
		$now = new DateTime(null, new DateTimeZone('America/Mexico_City'));
		$now=$now->format('Y-m-d H:i:s');					
		
		$freetrial=false;
		if($c["fechafin"]>=$now){
			$freetrial=true;
		}
		
        return $this->render('carrito', [
            'fdata' => $fdata,
            'u' => $u,
            'freetrial' => $freetrial,
        ]);
    }

    public function actionPagar()
    {

        $materiales=[];
        $moduloscliente = Modulocliente::find()->where(['cliente' => $_SESSION["usuario"],"estatus"=>"CARRITO"])->all();

        //pagar

        foreach($moduloscliente as $mc){
            $mc->estatus="PAGADO";
            $mc->save();
        }
        
        $moduloscliente = Modulocliente::find()->where(['cliente' => $_SESSION["usuario"],"estatus"=>"PAGADO"])->all();

        return $this->render('miscursos', [
            'moduloscliente' => $moduloscliente,
        ]);
    }

    public function actionMiscursos()
    {

        $materiales=[];
        $moduloscliente = Modulocliente::find()->where(['cliente' => $_SESSION["usuario"],"estatus"=>"PAGADO"])->all();
        
        return $this->render('miscursos', [
            'moduloscliente' => $moduloscliente,
        ]);
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
