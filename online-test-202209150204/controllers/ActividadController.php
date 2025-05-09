<?php

namespace app\controllers;

use Yii;
use app\models\Actividad;
use app\models\Edad;
use app\models\Familiar;
use app\models\Actividadfamiliarhistorico;
use app\models\ActividadSearch;
use yii\db\Query;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
require_once "../models/Funciones.php";

/**
 * ActividadController implements the CRUD actions for Actividad model.
 */
class ActividadController extends Controller
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
	
    public function actionAdminindex()
    {
		
		$searchModel = new ActividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('adminindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	

    /**
     * Lists all Actividad models.
     * @return mixed
     */
    public function actionIndex()
    {
		$familiarid=$_GET["familiarid"];
        $familiar = Familiar::find()->where(['usuario_id' => $_SESSION["usuario"], "familiar_id"=>$familiarid])->one();
		$edadm=calcularEdadMeses($familiar["fechanacimiento"],$familiar["semanasprematuro"]);
				
		$query = new Query;
		$query->select('edad_id')
			->from(['edad'])
			->where("edadnumerica<=".$edadm)
			->orderBy("edadnumerica desc");
						
		$results = $query->one();
		$edadid=1;
		if($results){
			$edadid= $results["edad_id"];
		}

		
		
		$actividadesq = new Query;
		$actividadesq->select('a.*')->addSelect(new Expression("count(if(afh.familiar_id=".$familiarid.",1,NULL)) as nh"))
			->from(['actividad a'])-> leftJoin("actividadfamiliarhistorico afh", "a.actividad_id = afh.actividad_id ")
			->where('a.edad_inferior_id<='.$edadid." AND a.edad_superior_id>=".$edadid)
			->groupBy("a.actividad_id")
			->orderBy("a.actividad_id ASC");

		$actividades = $actividadesq->all();
		
		//$actividades = Actividad::find()->where('edad_inferior_id<='.$edadid." AND edad_superior_id>=".$edadid)->all();

        return $this->render('index', [
            'actividades' => $actividades,
            'familiar' => $familiar,
        ]);
    }

    /**
     * Displays a single Actividad model.
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
     * Creates a new Actividad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Actividad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->actividad_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Actividad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$pagina="";
		if(isset($_GET["page"])){
			$pagina=$_GET["page"];
		}
				

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index.php?r=actividad/adminindex&page='.$pagina);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Actividad model.
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
     * Finds the Actividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Actividad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
