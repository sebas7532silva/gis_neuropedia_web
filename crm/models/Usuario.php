<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $email
 * @property string $password
 * @property string $nombre
 * @property string $apellido
 * @property string $telefono
 * @property string $perfil
 * @property string $estatus
 *
 * @property Cliente[] $clientes
 * @property Cliente[] $clientes0
 * @property Cliente[] $clientes1
 * @property Historial[] $historials
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'perfil', 'estatus'], 'required'],
            [['email'], 'string', 'max' => 200],
            [['password'], 'string', 'max' => 45],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 20],
            [['perfil'], 'string', 'max' => 35],
            [['estatus'], 'string', 'max' => 10],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'telefono' => 'Telefono',
            'perfil' => 'Perfil',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['asesor' => 'email']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes0()
    {
        return $this->hasMany(Cliente::className(), ['director_hipotecario' => 'email']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes1()
    {
        return $this->hasMany(Cliente::className(), ['gerente' => 'email']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorials()
    {
        return $this->hasMany(Historial::className(), ['usuario' => 'email']);
    }
}
