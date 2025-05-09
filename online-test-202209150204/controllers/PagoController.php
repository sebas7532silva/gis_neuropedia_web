<?php

namespace app\controllers;

use Yii;
use app\models\Pago;
use app\models\Pagolog;
use app\models\Producto;
use app\models\PagoSearch;
use app\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PagoController implements the CRUD actions for Pago model.
 */
class PagoController extends Controller
{
    public $enableCsrfValidation = false;

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
     * Lists all Pago models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PagoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pago model.
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
     * Creates a new Pago model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

		require_once  "../stripepay/config.php";
    
		$token = $_POST["stripeToken"];		
		$producto = $_POST["producto"];
					
		$nbproduct = Producto::find()->where('producto=:prd', array(':prd'=>$producto))->one();
		
		
		//Guardar Intento de Pago en Local
		$pago = new Pago();
		$pago["usuario"]=$_SESSION["usuario"];
		$pago["producto_id"]=$nbproduct["producto_id"];
		$pago["estatus"]="INICIANDO-PAGO";
		$pago["amount"]=$nbproduct["precio"];
			
		$pagolog = new Pagolog();
		$pagolog["usuario"]=$_SESSION["usuario"];
		$pagolog["producto_id"]=$nbproduct["producto_id"];
		$pagolog["estatus"]="INICIANDO-PAGO";
		$pagolog["amount"]=$nbproduct["precio"];
		
		$charge = false;
		
		$saveCharge="";
		$saveError="";
		$saveDeclineCode="";
		$saveStatus="";
		$saveMail="";
		$saveCard="";
		$saveRecepit="";
		$saveChargeObject="";
		$saveErrorObject="";

		try{

			//Intento de Pago Remoto
			if ($pago->save()&&$pagolog->save()) {
				$charge = \Stripe\Charge::create([
				  "amount" => $nbproduct["precio"],
				  "currency" => 'mxn',
				  "description"=>$nbproduct["producto"],
				  "source"=> $token,
				]);
			}else{
				//Intento de Guardado Local fallido
				$pago["usuario"]=$_SESSION["usuario"];
				$pago["producto_id"]=$nbproduct["producto_id"];
				$pago["estatus"]="ERROR-GUARDADO-PAGO-LOCAL";
				$pago["amount"]=$nbproduct["precio"];
				$pago->save();
				
				$pagolog = new Pagolog();
				$pagolog["usuario"]=$_SESSION["usuario"];
				$pagolog["producto_id"]=$nbproduct["producto_id"];
				$pagolog["estatus"]="ERROR-GUARDADO-PAGO-LOCAL";
				$pagolog["amount"]=$nbproduct["precio"];
				$pagolog->save();
			}
		

			if($charge){
				
				//Intento de Pago Remoto Exitoso
				if($producto=="nblogin"){
					$_SESSION["horasdisponibles"]+=2;
				}else{
					$_SESSION["horasdisponibles"]+=1;
				}
				$u=Usuario::Find()->where(['email' => $_SESSION["usuario"]])->one();
				$u["horasdisponibles"]=$_SESSION["horasdisponibles"];
				$u->save();
				
				$saveStatus="STRIPE-PAGO-EXITOSO";
				$saveCharge=$charge["id"];
				$saveMail=$charge["billing_details"]["name"];
				$saveCard=$charge["payment_method"];
				$saveRecepit=$charge["receipt_url"];	
				$saveChargeObject=var_export($charge,true);
				
			}

		} catch(\Stripe\Exception\CardException $e) {
			
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
			
			
		} catch (\Stripe\Exception\RateLimitException $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();

		} catch (\Stripe\Exception\InvalidRequestException $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
		} catch (\Stripe\Exception\AuthenticationException $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
		} catch (\Stripe\Exception\ApiErrorException $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
		} catch (Exception $e) {
			$saveStatus="STRIPE-PAGO-FALLIDO";
			$saveCharge=$e->getError()->charge;
			$saveDeclineCode=$e->getError()->decline_code;
			$saveErrorObject=$e->getTraceAsString();
		}	catch (Exception $e) {
					
		}
	
		//Guardar Resultado Stripe
		$pago["usuario"]=$_SESSION["usuario"];
		$pago["producto_id"]=$nbproduct["producto_id"];
		$pago["estatus"]=$saveStatus;
		$pago["amount"]=$nbproduct["precio"];
		$pago["charge"]=$saveCharge;
		$pago["error"]=$saveError;
		$pago["declinecode"]=$saveDeclineCode;
		$pago["mail"]=$saveMail;
		$pago["card"]=$saveCard;
		$pago["recipt"]=$saveRecepit;
		$pago["chargeobject"]=$saveChargeObject;
		$pago["errorobject"]=$saveErrorObject;
		$pago->save();

		
		$pagolog = new Pagolog();
		$pagolog["usuario"]=$_SESSION["usuario"];
		$pagolog["producto_id"]=$nbproduct["producto_id"];
		$pagolog["estatus"]=$saveStatus;
		$pagolog["amount"]=$nbproduct["precio"];
		$pagolog["charge"]=$saveCharge;
		$pagolog["error"]=$saveError;
		$pagolog["declinecode"]=$saveDeclineCode;
		$pagolog["mail"]=$saveMail;
		$pagolog["card"]=$saveCard;
		$pagolog["recipt"]=$saveRecepit;
		$pagolog["chargeobject"]=$saveChargeObject;
		$pagolog["errorobject"]=$saveErrorObject;
		
		$pagolog->save();
		
		$_SESSION["pagado"]="YES";
		$_SESSION["pagadoprecio"]=$nbproduct["precio"];
	
		return $this->redirect(array("familiar/index"));		

	
    }

    /**
     * Updates an existing Pago model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pago_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pago model.
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
     * Finds the Pago model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pago the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pago::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
