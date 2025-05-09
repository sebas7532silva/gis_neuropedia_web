<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EstatusCliente;

/**
 * EstatusClienteSearch represents the model behind the search form of `app\models\EstatusCliente`.
 */
class EstatusClienteSearch extends EstatusCliente
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estatus_cliente_id'], 'integer'],
            [['estatus', 'etapa', 'activo'], 'safe'],
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
        $query = EstatusCliente::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'estatus_cliente_id' => $this->estatus_cliente_id,
        ]);

        $query->andFilterWhere(['like', 'estatus', $this->estatus])
            ->andFilterWhere(['like', 'etapa', $this->etapa])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
