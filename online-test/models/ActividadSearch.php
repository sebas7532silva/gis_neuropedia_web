<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Actividad;

/**
 * ActividadSearch represents the model behind the search form of `app\models\Actividad`.
 */
class ActividadSearch extends Actividad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'examen_id', 'edad_inferior_id', 'edad_superior_id'], 'integer'],
            [['actividad'], 'safe'],
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
        $query = Actividad::find();

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
            'actividad_id' => $this->actividad_id,
            'examen_id' => $this->examen_id,
            'edad_inferior_id' => $this->edad_inferior_id,
            'edad_superior_id' => $this->edad_superior_id,
        ]);

        $query->andFilterWhere(['like', 'actividad', $this->actividad]);

        return $dataProvider;
    }
}
