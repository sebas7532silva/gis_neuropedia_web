<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edad".
 *
 * @property int $edad_id
 * @property string|null $edad
 * @property int|null $edadnumerica
 *
 * @property Actividad[] $actividads
 * @property Actividad[] $actividads0
 * @property Examenedadinterpretacion[] $examenedadinterpretacions
 * @property Examenpregunta[] $examenpreguntas
 * @property Famililarhistorico[] $famililarhistoricos
 */
class Edad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['edad_id'], 'required'],
            [['edad_id', 'edadnumerica'], 'integer'],
            [['edad'], 'string', 'max' => 100],
            [['edad_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'edad_id' => 'Edad ID',
            'edad' => 'Edad',
            'edadnumerica' => 'Edadnumerica',
        ];
    }

    /**
     * Gets query for [[Actividads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividads()
    {
        return $this->hasMany(Actividad::className(), ['edad_inferior_id' => 'edad_id']);
    }

    /**
     * Gets query for [[Actividads0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividads0()
    {
        return $this->hasMany(Actividad::className(), ['edad_superior_id' => 'edad_id']);
    }

    /**
     * Gets query for [[Examenedadinterpretacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamenedadinterpretacions()
    {
        return $this->hasMany(Examenedadinterpretacion::className(), ['edad_id' => 'edad_id']);
    }

    /**
     * Gets query for [[Examenpreguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamenpreguntas()
    {
        return $this->hasMany(Examenpregunta::className(), ['edad_id' => 'edad_id']);
    }

    /**
     * Gets query for [[Famililarhistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamililarhistoricos()
    {
        return $this->hasMany(Famililarhistorico::className(), ['edad_id' => 'edad_id']);
    }
}
