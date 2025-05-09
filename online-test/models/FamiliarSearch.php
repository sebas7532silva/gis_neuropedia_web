<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Familiar;

/**
 * FamiliarSearch represents the model behind the search form of `app\models\Familiar`.
 */
class FamiliarSearch extends Familiar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['familiar_id'], 'integer'],
            [['nombre', 'apellido', 'apellido2', 'fechanacimiento', 'usuario_id'], 'safe'],
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
        $query = Familiar::find();

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
            'familiar_id' => $this->familiar_id,
            'fechanacimiento' => $this->fechanacimiento,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'apellido2', $this->apellido2])
            ->andFilterWhere(['like', 'usuario_id', $this->usuario_id]);

        return $dataProvider;
    }
}
