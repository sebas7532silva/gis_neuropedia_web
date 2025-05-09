<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "competencia".
 *
 * @property int $competencia_id
 * @property string|null $competencia
 * @property string|null $color1
 * @property string|null $color2
 *
 * @property Examenedadinterpretacion[] $examenedadinterpretacions
 * @property Examenpregunta[] $examenpreguntas
 * @property Famililarhistorico[] $famililarhistoricos
 */
class Competencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['competencia'], 'string', 'max' => 100],
            [['color1', 'color2'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'competencia_id' => 'Competencia ID',
            'competencia' => 'Competencia',
            'color1' => 'Color1',
            'color2' => 'Color2',
        ];
    }

    /**
     * Gets query for [[Examenedadinterpretacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamenedadinterpretacions()
    {
        return $this->hasMany(Examenedadinterpretacion::className(), ['competencia_id' => 'competencia_id']);
    }

    /**
     * Gets query for [[Examenpreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamenpreguntas()
    {
        return $this->hasMany(Examenpregunta::className(), ['competencia_id' => 'competencia_id']);
    }

    /**
     * Gets query for [[Famililarhistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamililarhistoricos()
    {
        return $this->hasMany(Famililarhistorico::className(), ['competencia_id' => 'competencia_id']);
    }
}
