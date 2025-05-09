<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Codigodescuentousuario;

/**
 * CodigodescuentousuarioSearch represents the model behind the search form of `app\models\Codigodescuentousuario`.
 */
class CodigodescuentousuarioSearch extends Codigodescuentousuario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigodescuentousuario_id', 'porcentaje', 'pagototal'], 'integer'],
            [['usuario', 'fechaenvio', 'tipo', 'fechainicio', 'fechafin', 'estatus'], 'safe'],
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
        $query = Codigodescuentousuario::find();

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
            'codigodescuentousuario_id' => $this->codigodescuentousuario_id,
            'fechaenvio' => $this->fechaenvio,
            'porcentaje' => $this->porcentaje,
            'fechainicio' => $this->fechainicio,
            'fechafin' => $this->fechafin,
            'pagototal' => $this->pagototal,
        ]);

        $query->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'estatus', $this->estatus]);

        return $dataProvider;
    }
}
