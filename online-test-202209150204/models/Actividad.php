<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividad".
 *
 * @property int $actividad_id
 * @property int|null $examen_id
 * @property int|null $edad_inferior_id
 * @property int|null $edad_superior_id
 * @property string|null $actividad
 *
 * @property Examen $examen
 * @property Edad $edadInferior
 * @property Edad $edadSuperior
 */
class Actividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['examen_id', 'edad_inferior_id', 'edad_superior_id'], 'integer'],
            [['actividad'], 'string'],
            [['examen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Examen::className(), 'targetAttribute' => ['examen_id' => 'examen_id']],
            [['edad_inferior_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edad::className(), 'targetAttribute' => ['edad_inferior_id' => 'edad_id']],
            [['edad_superior_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edad::className(), 'targetAttribute' => ['edad_superior_id' => 'edad_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'actividad_id' => 'Actividad ID',
            'examen_id' => 'Examen ID',
            'edad_inferior_id' => 'Edad Inferior ID',
            'edad_superior_id' => 'Edad Superior ID',
            'actividad' => 'Actividad',
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
     * Gets query for [[EdadInferior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdadInferior()
    {
        return $this->hasOne(Edad::className(), ['edad_id' => 'edad_inferior_id']);
    }

    /**
     * Gets query for [[EdadSuperior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdadSuperior()
    {
        return $this->hasOne(Edad::className(), ['edad_id' => 'edad_superior_id']);
    }
}
