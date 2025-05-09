<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "producto".
 *
 * @property int $producto_id
 * @property string|null $producto
 * @property string|null $estatus
 * @property int|null $precio
 *
 * @property Pago[] $pagos
 * @property Pagolog[] $pagologs
 */
class Producto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['precio'], 'integer'],
            [['producto', 'estatus'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'producto_id' => 'Producto ID',
            'producto' => 'Producto',
            'estatus' => 'Estatus',
            'precio' => 'Precio',
        ];
    }

    /**
     * Gets query for [[Pagos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagos()
    {
        return $this->hasMany(Pago::className(), ['producto_id' => 'producto_id']);
    }

    /**
     * Gets query for [[Pagologs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagologs()
    {
        return $this->hasMany(Pagolog::className(), ['producto_id' => 'producto_id']);
    }
}
