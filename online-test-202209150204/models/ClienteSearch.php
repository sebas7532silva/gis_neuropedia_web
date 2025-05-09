<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
  
class ClienteSearch extends Cliente
{
    /**
     * {@inheritdoc}
     */
	public $directorHipotecario;
	public $gerente0;
	public $asesor0;
	public $estatusCliente;

    public function rules()
    {
        return [
            [['cliente_id', 'estatus_cliente_id'], 'integer'],
            [['nombre', 'prim_ap', 'seg_ap', 'cony_nombre', 'cony_prim_ap', 'cony_seg_ap', 'num_int', 'vcv', 'monto_credito', 'comentarios', 'otro_estatus', 'asesor', 'gerente', 'director_hipotecario','asesor0','gerente0','directorHipotecario','estatusCliente'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		
        $query = Cliente::find()->alias('cliente');

        // add conditions that should always apply here

		$query->joinWith(["asesor0 as asesor0"])->joinWith(["gerente0 as gerente0"])->joinWith(["directorHipotecario as directorHipotecario"])->joinWith(["estatusCliente as estatusCliente"]);

		if(isset($params["dhfx"])){
			$query->andFilterWhere(['like', 'director_hipotecario', $params["dhfx"]])
			;			
		}
		
		if(isset($params["gefx"])){
			$query->andFilterWhere(['like', 'gerente', $params["gefx"]])
			;			
		}

		if(isset($params["asfx"])){
			$query->andFilterWhere(['like', 'asesor', $params["asfx"]])
			;			
		}		

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->sort->attributes['asesor0'] = [
			'asc' => ['asesor0.nombre' => SORT_ASC],
			'desc' => ['asesor0.nombre' => SORT_DESC],
		];		
		
		$dataProvider->sort->attributes['gerente0'] = [
			'asc' => ['gerente0.email' => SORT_ASC],
			'desc' => ['gerente0.email' => SORT_DESC],
		];		
		
		$dataProvider->sort->attributes['directorHipotecario'] = [
			'asc' => ['directorHipotecario.email' => SORT_ASC],
			'desc' => ['directorHipotecario.email' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['estatusCliente'] = [
			'asc' => ['estatusCliente.estatus_cliente_id' => SORT_ASC],
			'desc' => ['estatusCliente.estatus_cliente_id' => SORT_DESC],
		];		


        if (!($this->load($params) )) {
			return $dataProvider;
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'cliente_id' => $this->cliente_id,
            'estatus_cliente_id' => $this->estatus_cliente_id,
        ]);
		

        $query->andFilterWhere(['like', 'Lower(cliente.nombre)', strtolower($this->nombre)])
            ->andFilterWhere(['like', 'Lower(cliente.prim_ap)', strtolower($this->prim_ap)])
            ->andFilterWhere(['like', 'Lower(cliente.seg_ap)', strtolower($this->seg_ap)])
            ->andFilterWhere(['like', 'cliente.cony_nombre', $this->cony_nombre])
            ->andFilterWhere(['like', 'cliente.cony_prim_ap', $this->cony_prim_ap])
            ->andFilterWhere(['like', 'cliente.cony_seg_ap', $this->cony_seg_ap])
            ->andFilterWhere(['like', 'Lower(cliente.num_int)', strtolower($this->num_int)])
            ->andFilterWhere(['like', 'Lower(cliente.vcv)', strtolower($this->vcv)])
            ->andFilterWhere(['like', 'cliente.monto_credito', $this->monto_credito])
            ->andFilterWhere(['like', 'cliente.comentarios', $this->comentarios])
            ->andFilterWhere(['like', 'cliente.otro_estatus', $this->otro_estatus])
            ->andFilterWhere(['like', 'asesor', $this->asesor0])
            ->andFilterWhere(['like', 'gerente', $this->gerente0])
            ->andFilterWhere(['like', 'director_hipotecario', $this->directorHipotecario])
			->andFilterWhere(['=', 'estatusCliente.estatus_cliente_id', $this->estatusCliente])
			;
			

        return $dataProvider;
    }
}
