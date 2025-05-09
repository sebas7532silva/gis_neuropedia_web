<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codigodescuento".
 *
 * @property int $codigodescuento_id
 * @property string|null $codigodescuento
 * @property int|null $porcentaje
 * @property string|null $fechainicio
 * @property string|null $fechafin
 * @property string|null $estatus
 *
 * @property Codigodescuentolog[] $codigodescuentologs
 */
class Codigodescuento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codigodescuento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['porcentaje'], 'integer'],
            [['fechainicio', 'fechafin'], 'safe'],
            [['codigodescuento'], 'string', 'max' => 50],
            [['estatus'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigodescuento_id' => 'Codigodescuento ID',
            'codigodescuento' => 'Codigodescuento',
            'porcentaje' => 'Porcentaje',
            'fechainicio' => 'Fechainicio',
            'fechafin' => 'Fechafin',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * Gets query for [[Codigodescuentologs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodigodescuentologs()
    {
        return $this->hasMany(Codigodescuentolog::className(), ['codigodescuento_id' => 'codigodescuento_id']);
    }
}
