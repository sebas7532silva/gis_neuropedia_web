<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagolog".
 *
 * @property int $pagolog_id
 * @property string|null $usuario
 * @property string|null $fecha
 * @property int|null $producto_id
 * @property string|null $estatus
 * @property int|null $amount
 * @property string|null $charge
 * @property string|null $error
 * @property string|null $declinecode
 * @property string|null $mail
 * @property string|null $card
 * @property string|null $recipt
 * @property string|null $chargeobject
 * @property string|null $errorobject
 *
 * @property Producto $producto
 */
class Pagolog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagolog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['producto_id', 'amount'], 'integer'],
            [['chargeobject', 'errorobject'], 'string'],
            [['usuario', 'charge', 'error', 'declinecode', 'mail', 'card'], 'string', 'max' => 200],
            [['estatus'], 'string', 'max' => 45],
            [['recipt'], 'string', 'max' => 500],
            [['producto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['producto_id' => 'producto_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pagolog_id' => 'Pagolog ID',
            'usuario' => 'Usuario',
            'fecha' => 'Fecha',
            'producto_id' => 'Producto ID',
            'estatus' => 'Estatus',
            'amount' => 'Amount',
            'charge' => 'Charge',
            'error' => 'Error',
            'declinecode' => 'Declinecode',
            'mail' => 'Mail',
            'card' => 'Card',
            'recipt' => 'Recipt',
            'chargeobject' => 'Chargeobject',
            'errorobject' => 'Errorobject',
        ];
    }

    /**
     * Gets query for [[Producto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::className(), ['producto_id' => 'producto_id']);
    }
}
