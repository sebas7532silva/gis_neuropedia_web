<?php

namespace app\controllers;

use Yii;
use app\models\Preguntausuariorespuesta;
use app\models\Preguntausuariorespuestahistorico;
use app\models\Copies;
use app\models\Competencia;
use app\models\Familiar;
use app\models\Examenpregunta;
use app\models\Familiarhistorico;
use app\models\Examenedadinterpretacion;
use app\models\PreguntausuariorespuestaSearch;
use app\models\Edad;
use app\models\Examen;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
require_once "../models/Funciones.php";

/**
 * PreguntausuariorespuestaController implements the CRUD actions for Preguntausuariorespuesta model.
 */
class PreguntausuariorespuestaController extends Controller
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
     * Lists all Preguntausuariorespuesta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreguntausuariorespuestaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Preguntausuariorespuesta model.
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
     * Creates a new Preguntausuariorespuesta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
		$familiarid=$_GET["familiarid"];
		
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();		
		$edms=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);
		$age=Edad::find()->where('edadnumerica<='.$edms)->orderBy("edadnumerica desc")->one()["edad_id"];		
		
		if(!$age){
			$age=1;
		}
		
		
        $model = new Preguntausuariorespuesta();
		$buscar=false;
		if(isset($_GET["busc"])){
			$buscar=$_GET["busc"];
		}
		
		if(!empty(Yii::$app->request->post())){
			$model->load(Yii::$app->request->post());
			$respuesta=Preguntausuariorespuesta::find()->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid,"pregunta_id"=>$model["pregunta_id"]])->one();		

			if($respuesta){
				$respuesta->load(Yii::$app->request->post());
				$respuesta["familiar_id"]=$familiarid;
				$respuesta->save();
			}else{
				$model["familiar_id"]=$familiarid;
				$model->save();
			}
			$buscar=false;
		}

		$ep;

		$query = new Query;
		$query->select('pregunta_id')
		->from('preguntausuariorespuesta')
		->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid])->groupBy("pregunta_id");
		$actualkeyans = $query->all();

		$query = new Query;
		$query->select('pregunta_id')
		->from('preguntausuariorespuestahistorico')
		->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid])->groupBy("pregunta_id");
		$anteriorkeyans = $query->all();
		
		$keyans=array_merge($actualkeyans, $anteriorkeyans);

		$excludeq="";
		if($keyans){
			$arstring="";
			foreach($keyans as $k){
				$arstring.=$k["pregunta_id"].",";
			}
			$arstring=rtrim($arstring, ", ");
			$excludeq="not pregunta_id IN (".(empty($keyans)?"0":$arstring).") AND ";	
			//echo $arstring;die();
		}
		

		if($buscar){
			$ep=Examenpregunta::find()->with('examen')->with('edad')->with('competencia')->where( ['pregunta_id' => $buscar, "edad_id"=>$age])->orderBy(['competencia_id' => SORT_ASC, 'orden' => SORT_ASC,])->one();
		}else{
			//$ep=Examenpregunta::find()->with('examen')->with('edad')->with('competencia')->where( ['not', ['pregunta_id' => $keyans]])->orderBy(['competencia_id' => SORT_ASC, 'orden' => SORT_ASC,])->one();
			$ep=Examenpregunta::find()->with('examen')->with('edad')->with('competencia')->where( $excludeq."  edad_id=".$age)->orderBy(['competencia_id' => SORT_ASC, 'orden' => SORT_ASC,])->one();
		}
		
		/*Presentar Resultados*/
		if($ep==null){
			
			$fh = Familiarhistorico::find()->where(['familiar_id' => $familiarid, "edad_id"=>$age])->one();
			$now = new \DateTime();
			$now = $now->format('Y-m-d H:i:s');
			
			//if(false){
			if($fh==null){

				$comps=Competencia::find()->orderBy(['competencia_id' => SORT_ASC])->all();
				
				foreach ($comps as $comp){
					if($comp["competencia_id"]>1&&$comp["competencia_id"]<7){
						$query = new Query;
						$query->select('respuesta')
							->from(['preguntausuariorespuesta pur', 'examenpregunta ep', 'examen e'])
							->where("pur.pregunta_id=ep.pregunta_id and e.examen_id=ep.examen_id AND e.examen_id=1 AND familiar_id=".$familiarid." AND usuario_id='".$_SESSION["usuario"]."' AND competencia_id=".$comp["competencia_id"]."");
						$results = $query->all();
						$score=0;
						
						foreach($results as $result){
							if($result["respuesta"]=="SÍ"){
								$score+=10;
							}elseif($result["respuesta"]=="A VECES"){
								$score+=5;
							}elseif($result["respuesta"]=="NO"){
								$score+=0;
							}
						}
						$fh = new Familiarhistorico();
						$fh["familiar_id"]=$familiarid;
						$fh["competencia_id"]=$comp["competencia_id"];
						$fh["edad_id"]=$age;
						$fh["resultado"]=$score;
						$fh["fecha"]=$now;
						$fh->save();
					}
				}
				Yii::$app->db->createCommand("INSERT INTO preguntausuariorespuestahistorico SELECT *, '".$now."' FROM preguntausuariorespuesta WHERE familiar_id =".$familiarid)->execute();	
				Yii::$app->db->createCommand("DELETE FROM preguntausuariorespuesta WHERE familiar_id =".$familiarid)->execute();	
				
			}
			

			$familiar=Familiar::find()->where(["familiar_id"=>$familiarid])->one();		
			
			$enviara="citas@dragisneuropedia.com";
			email($enviara,"Nuevo Examen Terminado: ".$familiar["nombre"]." ".$familiar["apellido"],"Examen Finalizado",$familiar["nombre"]." ".$familiar["apellido"].' terminó un examen',"Ver el Examen", 'https://dragisneuropedia.com/online-test/web/index.php?r=preguntausuariorespuesta%2Fresultadoagehistorico&familiarid='.$familiarid.'&edadid='.$age.'&fechaid='.$now,"mama.png","#F5F8FA");
			
			return $this->redirect(['preguntausuariorespuesta/resultadoage',"familiarid"=>$familiarid]);
		}
		
		$competencia=$ep["competencia"];
		
		$eps=ExamenPregunta::find()->with('examen')->with('edad')->with('competencia')->where("edad_id=".$age)->all();
		
		$competenciasq = new Query;
		$competenciasq->select('c.competencia_id, c.competencia, ep.pregunta_id ')
			->from(['competencia c', 'examenpregunta ep'])
			->where("c.competencia_id=ep.competencia_id AND ep.edad_id=".$age)->groupBy("c.competencia_id");
		$competencias = $competenciasq->all();
						
		$epc=Preguntausuariorespuesta::find()->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid, "pregunta_id"=>$ep["pregunta_id"]])->one();		
		
		$temp=[];
		foreach ($keyans as $a){
			array_push($temp,$a["pregunta_id"]);
		}
		
		$keyans=$temp;

        return $this->render('create', [
            'ep' => $ep,
            'eps' => $eps,			
			'model' => $model,
			'keyans'=>$keyans,
			'epc'=>$epc,
			'competencia'=>$competencia,
			'familiar'=>$familiar,
			'competencias'=>$competencias,
			'familiarid'=>$familiarid,
        ]);
		
    }
	
    public function actionResultadoage()
    {

		$familiarid=$_GET["familiarid"];
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();				
		$edms=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);
		$age=Edad::find()->where('edadnumerica<='.$edms)->orderBy("edadnumerica desc")->one()["edad_id"];
		
		if($age==null){
			$age=1;
		}

		$examenid=1;
		
		$age=Edad::find()->where( ['edad_id' => $age])->one();
		$test=Examen::find()->where( ['examen_id' => 1])->one();
		$fh = Familiarhistorico::find()->with('competencia')->where(['familiar_id' => $familiarid, "edad_id"=>$age["edad_id"]])->all();
		$inter = Examenedadinterpretacion::find()->where(['examen_id' => $examenid, "edad_id"=>$age["edad_id"]])->all();		
		$copyg = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-BUENO"])->one()["copy"];
		$copyw = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MALO"])->one()["copy"];
		$copym = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MEDIO"])->one()["copy"];
		
			return $this->render('resultadoage', [
				"age"=>$age,
				"test"=>$test,
				"inter"=>$inter,
				"fh"=>$fh,
				"copyg"=>$copyg,
				"familiar"=>$familiar,
				"copyw"=>$copyw,
				"copym"=>$copym,
			]);
    }	
	
    public function actionResultadoagehistorico()
    {

		$familiarid=$_GET["familiarid"];
		$age=$_GET["edadid"];
		$examenid=1;
		$fechaid=$_GET["fechaid"];
		$enviar=false;
		
		if(isset($_GET["enviar"])){
			$enviar=true;
			$fh = Familiarhistorico::find()->with('competencia')->where(['familiar_id' => $familiarid, "edad_id"=>$age, "fecha"=>$fechaid])->all();
			foreach($fh as $f){
				$f["revision"]=1;
				$f->save();
			}
		}
		
		$age=Edad::find()->where( ['edad_id' => $age])->one();
		$test=Examen::find()->where( ['examen_id' => 1])->one();
		$fh = Familiarhistorico::find()->with('competencia')->where(['familiar_id' => $familiarid, "edad_id"=>$age, "fecha"=>$fechaid])->all();
		$inter = Examenedadinterpretacion::find()->where(['examen_id' => $examenid, "edad_id"=>$age])->all();
		$copyg = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-BUENO"])->one()["copy"];
		$copyw = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MALO"])->one()["copy"];
		$copym = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MEDIO"])->one()["copy"];
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();		
		
			return $this->render('resultadoagehistorico', [
				"age"=>$age,
				"test"=>$test,
				"inter"=>$inter,
				"fh"=>$fh,
				"copyg"=>$copyg,
				"familiar"=>$familiar,
				"copyw"=>$copyw,
				"copym"=>$copym,
				"enviar"=>$enviar,
			]);
    }	
	
	
    public function actionResultadoagehistoricodetalle()
    {

		$familiarid=$_GET["familiarid"];
		$edadid=$_GET["edadid"];
		$age=$_GET["edadid"];
		$examenid=1;
		$fechaid=$_GET["fechaid"];
		$competenciaid=$_GET["competenciaid"];
				
		$comp=Competencia::find()->where( ['competencia_id' => $competenciaid])->one();
		$age=Edad::find()->where( ['edad_id' => $age])->one();
		$fh = Preguntausuariorespuestahistorico::find()->joinWith('pregunta')->where(['familiar_id' => $familiarid, "fecha"=>$fechaid, "competencia_id"=>$competenciaid])->all();
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();		
		
			return $this->render('examendetalle', [
				"age"=>$age,
				"fh"=>$fh,
				"familiar"=>$familiar,
				"comp"=>$comp,
				"fechaid"=>$fechaid,
				"edadid"=>$edadid,
				"age"=>$age,
				"familiarid"=>$familiarid,
			]);
    }	
	
	
    public function actionResultadoagehistoricodetalleprint()
    {

		$familiarid=$_GET["familiarid"];
		$edadid=$_GET["edadid"];
		$age=$_GET["edadid"];
		$examenid=1;
		$fechaid=$_GET["fechaid"];
		$competenciaid=$_GET["competenciaid"];
				
		$comp=Competencia::find()->all();
		$age=Edad::find()->where( ['edad_id' => $age])->one();
		$fh = Preguntausuariorespuestahistorico::find()->joinWith('pregunta')->where(['familiar_id' => $familiarid, "fecha"=>$fechaid])->all();
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();		
		
			return $this->render('examendetalleprint', [
				"age"=>$age,
				"fh"=>$fh,
				"familiar"=>$familiar,
				"comp"=>$comp,
				"fechaid"=>$fechaid,
				"edadid"=>$edadid,
				"age"=>$age,
				"familiarid"=>$familiarid,
			]);
    }	
	
	
	
    public function actionExamenes()
    {

		$familiarid=$_GET["familiarid"];
		$familiar=Familiar::find()->where('familiar_id=:fid', array(':fid'=>$familiarid))->one();				
		$edms=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);
		$age=Edad::find()->where('edadnumerica<='.$edms)->orderBy("edadnumerica desc")->one()["edad_id"];		
		$examenid=1;
		
		$age=Edad::find()->where( ['edad_id' => $age])->one();
		$tests = Familiarhistorico::find()->with("edad")->with('competencia')->where(['familiar_id' => $familiarid])->all();
		$inter = Examenedadinterpretacion::find()->where(['examen_id' => $examenid])->all();
		$copyg = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-BUENO"])->one()["copy"];
		$copyw = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MALO"])->one()["copy"];
		$copym = Copies::find()->where(['copy_id' => "GEN-AS-RESULTADO-MEDIO"])->one()["copy"];
		
			return $this->render('examenes', [
				"age"=>$age,
				"tests"=>$tests,
				"inter"=>$inter,
				"copyg"=>$copyg,
				"familiar"=>$familiar,
				"copyw"=>$copyw,
				"copym"=>$copym,
			]);
    }	
	
	

    /**
     * Updates an existing Preguntausuariorespuesta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->respuesta_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Preguntausuariorespuesta model.
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
     * Finds the Preguntausuariorespuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Preguntausuariorespuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Preguntausuariorespuesta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
