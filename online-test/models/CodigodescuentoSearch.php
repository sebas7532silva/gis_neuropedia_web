<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Codigodescuento;

/**
 * CodigodescuentoSearch represents the model behind the search form of `app\models\Codigodescuento`.
 */
class CodigodescuentoSearch extends Codigodescuento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigodescuento_id', 'porcentaje'], 'integer'],
            [['codigodescuento', 'fechainicio', 'fechafin', 'estatus'], 'safe'],
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
        $query = Codigodescuento::find();

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
            'codigodescuento_id' => $this->codigodescuento_id,
            'porcentaje' => $this->porcentaje,
            'fechainicio' => $this->fechainicio,
            'fechafin' => $this->fechafin,
        ]);

        $query->andFilterWhere(['like', 'codigodescuento', $this->codigodescuento])
            ->andFilterWhere(['like', 'estatus', $this->estatus]);

        return $dataProvider;
    }
}
