<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use app\models\EstatusCliente;
use app\models\Historial;
use app\models\Usuario;
use app\models\ClienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
 
 
class ClienteController extends Controller
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
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
		$modif=true;
		
        $searchModel = new ClienteSearch();
		
		$dataProvider="";
		
		$us=Usuario::Find()->where(["email"=>$_SESSION["usuario"]])->one();
		
		if($_SESSION["perfil"]=="DIRECTOR HIPOTECARIO"){
			$qp=Yii::$app->request->queryParams;
			$qp["dhfx"]=$us->email;
			Yii::$app->request->queryParams=$qp;			
		}elseif($_SESSION["perfil"]=="GERENTE INMOBILIARIO"){
			$qp=Yii::$app->request->queryParams;
			$qp["gefx"]=$us->email;
			Yii::$app->request->queryParams=$qp;			
		}elseif($_SESSION["perfil"]=="ASESOR INMOBILIARIO"){
			$qp=Yii::$app->request->queryParams;
			$qp["asfx"]=$us->email;
			Yii::$app->request->queryParams=$qp;			
		}
		
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$eca=EstatusCliente::Find()->orderBy(["etapa"=>SORT_ASC,"estatus_cliente_id"=>SORT_ASC])->all();
		$ecal=ArrayHelper::map($eca,'estatus_cliente_id',  function($model) {
			return $model['etapa'].'---'.$model['estatus'];
		});	
		
		
		$di=Usuario::Find()->where(['perfil' => 'DIRECTOR HIPOTECARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		if($_SESSION["perfil"]=="DIRECTOR HIPOTECARIO"){
			$dil[$us->email]=$us->nombre." ".$us->apellido;
		}else{
			$dil=ArrayHelper::map($di,'email',  function($model) {
				return $model['nombre'].' '.$model['apellido'];
			});			
		}
		
		$ai=Usuario::Find()->where(['perfil' => 'ASESOR INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		if($_SESSION["perfil"]=="ASESOR INMOBILIARIO"){
			$ail[$us->email]=$us->nombre." ".$us->apellido;
		}else{
			$ail=ArrayHelper::map($ai,'email',  function($model) {
				return $model['nombre'].' '.$model['apellido'];
			});
		}
		
		$gi=Usuario::Find()->where(['perfil' => 'GERENTE INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		if($_SESSION["perfil"]=="GERENTE INMOBILIARIO"){
			$gil[$us->email]=$us->nombre." ".$us->apellido;
		}else{
			$gil=ArrayHelper::map($gi,'email',  function($model) {
				return $model['nombre'].' '.$model['apellido'];
			});	
		}		
		

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'ecal' => $ecal,
			'dil' => $dil,
			'ail' => $ail,
			'gil' => $gil,
			'modif' => $modif,
        ]);
    }

    /**
     * Displays a single Cliente model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
		$modif=false;
		if(in_array($_SESSION["perfil"],constant("MODIFICAR"))){
			$modif=true;
		}
				
		//$model = $this->findModel($id);
		$model = Cliente::find()->where(["cliente_id"=>$id])->with("asesor0")->with("gerente0")->with("directorHipotecario")->one();
		
		if(!in_array($_SESSION["perfil"],constant("TODOS"))){
			if($_SESSION["perfil"]=="DIRECTOR HIPOTECARIO"){
				if($model->director_hipotecario!=$_SESSION["usuario"]){
					die();
				}
			}elseif($_SESSION["perfil"]=="GERENTE INMOBILIARIO"){
				if($model->gerente!=$_SESSION["usuario"]){
					die();
				}
			}elseif($_SESSION["perfil"]=="ASESOR INMOBILIARIO"){
				if($model->asesor!=$_SESSION["usuario"]){
					die();
				}
			}
		}		
		
		$eca=EstatusCliente::Find()->all();
		$ecal=ArrayHelper::map($eca,'estatus_cliente_id',  function($model) {
			return $model['etapa'].'---'.$model['estatus'];
		});
				
        return $this->render('view', [
            'model' => $model,
			'estatus_cliente' => EstatusCliente::Find()->where(['estatus_cliente_id' => $model->estatus_cliente_id])->one(),
			'historial' => Historial::Find()->with("usuario0")->where(['cliente_id' => $model->cliente_id])->asArray()->all(),
			'ecal' => $ecal,
			'modif' => $modif,
        ]);
    }

    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}		
		
		$modif=false;
		if(in_array($_SESSION["perfil"],constant("MODIFICAR"))){
			$modif=true;
		}		
		
		if(!$modif){
			die();
		}
		
		
        $model = new Cliente();



		$di=Usuario::Find()->where(['perfil' => 'DIRECTOR HIPOTECARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$dil=ArrayHelper::map($di,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});
		
		$ai=Usuario::Find()->where(['perfil' => 'ASESOR INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$ail=ArrayHelper::map($ai,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});
		
		$gi=Usuario::Find()->where(['perfil' => 'GERENTE INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$gil=ArrayHelper::map($gi,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});
		
		$ec=Yii::$app->db->createCommand('SELECT etapa FROM estatus_cliente GROUP BY etapa')->queryAll();
		$ecl=ArrayHelper::map($ec,'etapa', 'etapa');		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$h = new Historial();
			$h->cliente_id=$model->cliente_id;
			$h->estatus_cliente_id=Yii::$app->request->post()["Cliente"]["estatus_cliente_id"];
			$us = Usuario::Find()->where(['email' => $_SESSION["usuario"]])->one();
			$h->usuario=$us->email;
			$h->otro=$model->otro_estatus;
			$h->save();			
			
            return $this->redirect(['view', 'id' => $model->cliente_id]);
        }

        return $this->render('create', [
            'model' => $model,
			'dil'=>$dil,
			'ail'=>$ail,
			'gil'=>$gil,
			'ecl'=>$ecl,
        ]);
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}
		
		$modif=false;
		if(in_array($_SESSION["perfil"],constant("MODIFICAR"))){
			$modif=true;
		}

		if(!$modif){
			die();
		}
		
        $model = $this->findModel($id);
		
		if(!in_array($_SESSION["perfil"],constant("TODOS"))){
			if($_SESSION["perfil"]=="DIRECTOR HIPOTECARIO"){
				if($model->director_hipotecario!=$_SESSION["usuario"]){
					die();
				}
			}elseif($_SESSION["perfil"]=="GERENTE INMOBILIARIO"){
				if($model->gerente!=$_SESSION["usuario"]){
					die();
				}
			}elseif($_SESSION["perfil"]=="ASESOR INMOBILIARIO"){
				if($model->asesor!=$_SESSION["usuario"]){
					die();
				}
			}
		}			
		
		if (Yii::$app->request->post()) {
			
			if(!empty(Yii::$app->request->post()["Cliente"]["estatus_cliente_id"])){
				if(Yii::$app->request->post()["Cliente"]["estatus_cliente_id"]!=$model->estatus_cliente_id||Yii::$app->request->post()["Cliente"]["otro_estatus"]!=$model->otro_estatus){
					$h = new Historial();
					$h->cliente_id=$model->cliente_id;
					$h->estatus_cliente_id=Yii::$app->request->post()["Cliente"]["estatus_cliente_id"];
					$us = Usuario::Find()->where(['email' => $_SESSION["usuario"]])->one();
					$h->usuario=$us->email;
					$h->otro=Yii::$app->request->post()["Cliente"]["otro_estatus"];
					$h->save(false);
				};
			}
        }
		
				
		$di=Usuario::Find()->where(['perfil' => 'DIRECTOR HIPOTECARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$dil=ArrayHelper::map($di,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});
		
		$ai=Usuario::Find()->where(['perfil' => 'ASESOR INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$ail=ArrayHelper::map($ai,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});
		
		$gi=Usuario::Find()->where(['perfil' => 'GERENTE INMOBILIARIO'])->orderBy(["nombre"=>SORT_ASC,"apellido"=>SORT_ASC])->all();
		$gil=ArrayHelper::map($gi,'email',  function($model) {
			return $model['nombre'].' '.$model['apellido'];
		});

		$ec=Yii::$app->db->createCommand('SELECT etapa FROM estatus_cliente GROUP BY etapa')->queryAll();
		$ecl=ArrayHelper::map($ec,'etapa', 'etapa');		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cliente_id]);
        }
		
		$ecs=EstatusCliente::Find()->orderBy(["etapa"=>SORT_ASC,"estatus_cliente_id"=>SORT_ASC])->all();
		$ecsl=ArrayHelper::map($ecs,'estatus_cliente_id',  function($model) {
			return $model['estatus'];
		});	
		
		$estact=EstatusCliente::Find()->where(["estatus_cliente_id"=>$model->estatus_cliente_id])->one();

        return $this->render('update', [
            'model' => $model,
			'dil'=>$dil,
			'ail'=>$ail,
			'gil'=>$gil,
			'ecl'=>$ecl,
			'ecsl'=>$ecsl,
			'estact'=>$estact,
			'modif'=>$modif,			
        ]);
    }

    /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
/*    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
*/
    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(!isset($_SESSION["usuario"])){
			return Yii::$app->response->redirect(['usuario/login']);
		}		
		
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
