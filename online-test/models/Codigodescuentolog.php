<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codigodescuentolog".
 *
 * @property int $codigodescuentolog
 * @property int|null $codigodescuento_id
 * @property string|null $usuario
 * @property string|null $fechauso
 * @property int|null $pagototal
 * @property string|null $estatus
 *
 * @property Codigodescuento $codigodescuento
 * @property Usuario $usuario0
 */
class Codigodescuentolog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codigodescuentolog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigodescuento_id', 'pagototal'], 'integer'],
            [['fechauso'], 'safe'],
            [['usuario'], 'string', 'max' => 200],
            [['estatus'], 'string', 'max' => 45],
            [['codigodescuento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Codigodescuento::className(), 'targetAttribute' => ['codigodescuento_id' => 'codigodescuento_id']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigodescuentolog' => 'Codigodescuentolog',
            'codigodescuento_id' => 'Codigodescuento ID',
            'usuario' => 'Usuario',
            'fechauso' => 'Fechauso',
            'pagototal' => 'Pagototal',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * Gets query for [[Codigodescuento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodigodescuento()
    {
        return $this->hasOne(Codigodescuento::className(), ['codigodescuento_id' => 'codigodescuento_id']);
    }

    /**
     * Gets query for [[Usuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'usuario']);
    }
}
