<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividadfamiliarhistorico".
 *
 * @property int $actividadhistorico_id
 * @property int|null $actividad_id
 * @property int|null $familiar_id
 * @property string|null $fecha
 * @property string|null $notas
 *
 * @property Actividad $actividad
 * @property Familiar $familiar
 */
class Actividadfamiliarhistorico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividadfamiliarhistorico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'familiar_id'], 'integer'],
            [['fecha'], 'safe'],
            [['notas'], 'string'],
            [['actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Actividad::className(), 'targetAttribute' => ['actividad_id' => 'actividad_id']],
            [['familiar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Familiar::className(), 'targetAttribute' => ['familiar_id' => 'familiar_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'actividadhistorico_id' => 'Actividadhistorico ID',
            'actividad_id' => 'Actividad ID',
            'familiar_id' => 'Familiar ID',
            'fecha' => 'Fecha',
            'notas' => 'Notas',
        ];
    }

    /**
     * Gets query for [[Actividad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividad()
    {
        return $this->hasOne(Actividad::className(), ['actividad_id' => 'actividad_id']);
    }

    /**
     * Gets query for [[Familiar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamiliar()
    {
        return $this->hasOne(Familiar::className(), ['familiar_id' => 'familiar_id']);
    }
}
