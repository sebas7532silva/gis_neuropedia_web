<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historial;

/**
 * HistorialSearch represents the model behind the search form of `app\models\Historial`.
 */
class HistorialSearch extends Historial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['historial_id', 'estatus_cliente_id'], 'integer'],
            [['fecha', 'usuario'], 'safe'],
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
        $query = Historial::find();

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
            'historial_id' => $this->historial_id,
            'estatus_cliente_id' => $this->estatus_cliente_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
