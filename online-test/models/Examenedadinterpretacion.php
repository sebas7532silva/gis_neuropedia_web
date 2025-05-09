<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "examenedadinterpretacion".
 *
 * @property int $interpretacion_id
 * @property int|null $examen_id
 * @property int|null $edad_id
 * @property int|null $competencia_id
 * @property float|null $minimo
 * @property float|null $intermedio
 *
 * @property Examen $examen
 * @property Competencia $competencia
 * @property Edad $edad
 */
class Examenedadinterpretacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'examenedadinterpretacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['examen_id', 'edad_id', 'competencia_id'], 'integer'],
            [['minimo', 'intermedio'], 'number'],
            [['examen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Examen::className(), 'targetAttribute' => ['examen_id' => 'examen_id']],
            [['competencia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Competencia::className(), 'targetAttribute' => ['competencia_id' => 'competencia_id']],
            [['edad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edad::className(), 'targetAttribute' => ['edad_id' => 'edad_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'interpretacion_id' => 'Interpretacion ID',
            'examen_id' => 'Examen ID',
            'edad_id' => 'Edad ID',
            'competencia_id' => 'Competencia ID',
            'minimo' => 'Minimo',
            'intermedio' => 'Intermedio',
        ];
    }

    /**
     * Gets query for [[Examen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamen()
    {
        return $this->hasOne(Examen::className(), ['examen_id' => 'examen_id']);
    }

    /**
     * Gets query for [[Competencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencia()
    {
        return $this->hasOne(Competencia::className(), ['competencia_id' => 'competencia_id']);
    }

    /**
     * Gets query for [[Edad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdad()
    {
        return $this->hasOne(Edad::className(), ['edad_id' => 'edad_id']);
    }
}
