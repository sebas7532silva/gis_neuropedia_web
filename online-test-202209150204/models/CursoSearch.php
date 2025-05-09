<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Curso;

/**
 * CursoSearch represents the model behind the search form of `app\models\Curso`.
 */
class CursoSearch extends Curso
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_id', 'tipo_id'], 'integer'],
            [['titulo', 'descripcion', 'ubicacion', 'sesiones', 'horas', 'presentacion', 'objetivos', 'contenido', 'unidades', 'acreditacion', 'bibliografia'], 'safe'],
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
        $query = Curso::find();

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
            'curso_id' => $this->curso_id,
            'tipo_id' => $this->tipo_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'sesiones', $this->sesiones])
            ->andFilterWhere(['like', 'horas', $this->horas])
            ->andFilterWhere(['like', 'presentacion', $this->presentacion])
            ->andFilterWhere(['like', 'objetivos', $this->objetivos])
            ->andFilterWhere(['like', 'contenido', $this->contenido])
            ->andFilterWhere(['like', 'unidades', $this->unidades])
            ->andFilterWhere(['like', 'acreditacion', $this->acreditacion])
            ->andFilterWhere(['like', 'bibliografia', $this->bibliografia]);

        return $dataProvider;
    }
}
