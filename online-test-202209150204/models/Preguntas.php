<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas".
 *
 * @property int $pregunta_id
 * @property int $examen_id
 * @property int $orden
 * @property string $texto
 *
 * @property Preguntausuariorespuesta[] $preguntausuariorespuestas
 */
class Preguntas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preguntas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['examen_id', 'orden', 'texto'], 'required'],
            [['examen_id', 'orden'], 'integer'],
            [['texto'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pregunta_id' => 'Pregunta ID',
            'examen_id' => 'Examen ID',
            'orden' => 'Orden',
            'texto' => 'Texto',
        ];
    }

    /**
     * Gets query for [[Preguntausuariorespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestas()
    {
        return $this->hasMany(Preguntausuariorespuesta::className(), ['pregunta_id' => 'pregunta_id']);
    }
}
