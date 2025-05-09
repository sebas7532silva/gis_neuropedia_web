<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estatus_cliente".
 *
 * @property int $estatus_cliente_id
 * @property string $estatus
 * @property string $etapa
 * @property string $activo
 *
 * @property Cliente[] $clientes
 * @property Historial[] $historials
 */
class EstatusCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estatus_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estatus', 'etapa', 'activo'], 'required'],
            [['estatus'], 'string', 'max' => 50],
            [['etapa'], 'string', 'max' => 20],
            [['activo'], 'string', 'max' => 35],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'estatus_cliente_id' => 'Estatus Cliente ID',
            'estatus' => 'Estatus',
            'etapa' => 'Etapa',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['estatus_cliente_id' => 'estatus_cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorials()
    {
        return $this->hasMany(Historial::className(), ['estatus_cliente_id' => 'estatus_cliente_id']);
    }
}
