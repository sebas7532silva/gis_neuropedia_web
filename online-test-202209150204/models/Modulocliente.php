<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modulocliente".
 *
 * @property int $modulo
 * @property string $cliente
 * @property string $estatus
 *
 * @property Usuario $cliente0
 * @property Modulo $modulo0
 */
class Modulocliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modulocliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modulo', 'cliente', 'estatus'], 'required'],
            [['modulo'], 'integer'],
            [['cliente'], 'string', 'max' => 200],
            [['estatus'], 'string', 'max' => 45],
            [['modulo', 'cliente'], 'unique', 'targetAttribute' => ['modulo', 'cliente']],
            [['cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['cliente' => 'email']],
            [['modulo'], 'exist', 'skipOnError' => true, 'targetClass' => Modulo::className(), 'targetAttribute' => ['modulo' => 'modulo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'modulo' => 'Modulo',
            'cliente' => 'Cliente',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * Gets query for [[Cliente0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'cliente']);
    }

    /**
     * Gets query for [[Modulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModulo0()
    {
        return $this->hasOne(Modulo::className(), ['modulo_id' => 'modulo']);
    }
}
