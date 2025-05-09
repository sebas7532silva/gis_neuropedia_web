<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modulo;

/**
 * ModuloSearch represents the model behind the search form of `app\models\Modulo`.
 */
class ModuloSearch extends Modulo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modulo_id', 'curso_id'], 'integer'],
            [['titulo', 'video', 'ejercicios', 'horas_practicas', 'horas_teoricas', 'usuario_id'], 'safe'],
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
        $query = Modulo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->where('curso_id='.$params["curso_id"]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'modulo_id' => $this->modulo_id,
            'curso_id' => $this->curso_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'ejercicios', $this->ejercicios])
            ->andFilterWhere(['like', 'horas_practicas', $this->horas_practicas])
            ->andFilterWhere(['like', 'horas_teoricas', $this->horas_teoricas])
            ->andFilterWhere(['like', 'usuario_id', $this->usuario_id]);

        return $dataProvider;
    }
}
