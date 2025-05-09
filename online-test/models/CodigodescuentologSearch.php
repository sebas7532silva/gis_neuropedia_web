<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Codigodescuentolog;

/**
 * CodigodescuentologSearch represents the model behind the search form of `app\models\Codigodescuentolog`.
 */
class CodigodescuentologSearch extends Codigodescuentolog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigodescuentolog', 'codigodescuento_id', 'pagototal'], 'integer'],
            [['usuario', 'fechauso', 'estatus'], 'safe'],
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
        $query = Codigodescuentolog::find();

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
            'codigodescuentolog' => $this->codigodescuentolog,
            'codigodescuento_id' => $this->codigodescuento_id,
            'fechauso' => $this->fechauso,
            'pagototal' => $this->pagototal,
        ]);

        $query->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'estatus', $this->estatus]);

        return $dataProvider;
    }
}
