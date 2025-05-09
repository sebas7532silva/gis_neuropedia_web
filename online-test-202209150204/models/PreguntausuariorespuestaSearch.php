<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Preguntausuariorespuesta;

/**
 * PreguntausuariorespuestaSearch represents the model behind the search form of `app\models\Preguntausuariorespuesta`.
 */
class PreguntausuariorespuestaSearch extends Preguntausuariorespuesta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['respuesta_id', 'pregunta_id'], 'integer'],
            [['usuario_id', 'respuesta'], 'safe'],
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
        $query = Preguntausuariorespuesta::find();

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
            'respuesta_id' => $this->respuesta_id,
            'pregunta_id' => $this->pregunta_id,
        ]);

        $query->andFilterWhere(['like', 'usuario_id', $this->usuario_id])
            ->andFilterWhere(['like', 'respuesta', $this->respuesta]);

        return $dataProvider;
    }
}
