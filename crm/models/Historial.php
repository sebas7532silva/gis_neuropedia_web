<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historial".
 *
 * @property int $historial_id
 * @property int $cliente_id
 * @property int $estatus_cliente_id
 * @property string $fecha
 * @property string $usuario
 * @property string $otro
 *
 * @property Cliente $cliente
 * @property EstatusCliente $estatusCliente
 * @property Usuario $usuario0
 */
class Historial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'estatus_cliente_id', 'usuario'], 'required'],
            [['cliente_id', 'estatus_cliente_id'], 'integer'],
            [['fecha'], 'safe'],
            [['usuario'], 'string', 'max' => 200],
            [['otro'], 'string', 'max' => 45],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'cliente_id']],
            [['estatus_cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstatusCliente::className(), 'targetAttribute' => ['estatus_cliente_id' => 'estatus_cliente_id']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'historial_id' => 'Historial ID',
            'cliente_id' => 'Cliente ID',
            'estatus_cliente_id' => 'Estatus Cliente ID',
            'fecha' => 'Fecha',
            'usuario' => 'Usuario',
            'otro' => 'Otro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['cliente_id' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstatusCliente()
    {
        return $this->hasOne(EstatusCliente::className(), ['estatus_cliente_id' => 'estatus_cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'usuario']);
    }
}
