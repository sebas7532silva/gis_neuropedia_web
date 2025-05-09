<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "examenpregunta".
 *
 * @property int $pregunta_id
 * @property int|null $examen_id
 * @property int|null $edad_id
 * @property int|null $competencia_id
 * @property int|null $orden
 * @property string|null $pregunta
 * @property string|null $imagen
 *
 * @property Competencia $competencia
 * @property Examen $examen
 * @property Edad $edad
 * @property Preguntausuariorespuesta[] $preguntausuariorespuestas
 * @property Preguntausuariorespuestahistorico[] $preguntausuariorespuestahistoricos
 */
class Examenpregunta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'examenpregunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['examen_id', 'edad_id', 'competencia_id', 'orden'], 'integer'],
            [['pregunta'], 'string'],
            [['imagen'], 'string', 'max' => 45],
            [['competencia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Competencia::className(), 'targetAttribute' => ['competencia_id' => 'competencia_id']],
            [['examen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Examen::className(), 'targetAttribute' => ['examen_id' => 'examen_id']],
            [['edad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edad::className(), 'targetAttribute' => ['edad_id' => 'edad_id']],
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
            'edad_id' => 'Edad ID',
            'competencia_id' => 'Competencia ID',
            'orden' => 'Orden',
            'pregunta' => 'Pregunta',
            'imagen' => 'Imagen',
        ];
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
     * Gets query for [[Examen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamen()
    {
        return $this->hasOne(Examen::className(), ['examen_id' => 'examen_id']);
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

    /**
     * Gets query for [[Preguntausuariorespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestas()
    {
        return $this->hasMany(Preguntausuariorespuesta::className(), ['pregunta_id' => 'pregunta_id']);
    }

    /**
     * Gets query for [[Preguntausuariorespuestahistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestahistoricos()
    {
        return $this->hasMany(Preguntausuariorespuestahistorico::className(), ['pregunta_id' => 'pregunta_id']);
    }
}
