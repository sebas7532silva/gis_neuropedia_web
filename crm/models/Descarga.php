<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "descarga".
 *
 * @property int $id
 * @property string|null $nombreapellido
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $ip
 * @property string|null $fecha
 * @property string|null $data
 */
class Descarga extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'descarga';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['nombreapellido'], 'string', 'max' => 1000],
            [['email'], 'string', 'max' => 500],
            [['telefono'], 'string', 'max' => 45],
            [['ip'], 'string', 'max' => 20],
            [['data'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombreapellido' => 'Nombreapellido',
            'email' => 'Email',
            'telefono' => 'Telefono',
            'ip' => 'Ip',
            'fecha' => 'Fecha',
            'data' => 'Data',
        ];
    }
}
