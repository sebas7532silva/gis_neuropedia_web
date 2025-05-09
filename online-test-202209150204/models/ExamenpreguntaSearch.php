<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Examenpregunta;

/**
 * ExamenpreguntaSearch represents the model behind the search form of `app\models\Examenpregunta`.
 */
class ExamenpreguntaSearch extends Examenpregunta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta_id', 'examen_id', 'edad_id', 'competencia_id'], 'integer'],
            [['pregunta'], 'safe'],
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
        $query = Examenpregunta::find();

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
            'pregunta_id' => $this->pregunta_id,
            'examen_id' => $this->examen_id,
            'edad_id' => $this->edad_id,
            'competencia_id' => $this->competencia_id,
        ]);

        $query->andFilterWhere(['like', 'pregunta', $this->pregunta]);

        return $dataProvider;
    }
}
