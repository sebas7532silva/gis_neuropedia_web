<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $email
 * @property string|null $password
 * @property string|null $nombre
 * @property string|null $apellido
 * @property string $perfil
 * @property string $estatus
 * @property int|null $horasdisponibles
 * @property string|null $codigodescuento
 *
 * @property Codigodescuentolog[] $codigodescuentologs
 * @property Familiar[] $familiars
 * @property Preguntausuariorespuesta[] $preguntausuariorespuestas
 * @property Preguntausuariorespuestahistorico[] $preguntausuariorespuestahistoricos
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
            [['horasdisponibles'], 'integer'],
            [['email', 'codigodescuento'], 'string', 'max' => 200],
            [['password'], 'string', 'max' => 45],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['perfil'], 'string', 'max' => 35],
            [['estatus'], 'string', 'max' => 10],
            [['email'], 'unique',  'message' => 'Este correo ya existe, favor de <a style="color:blue; font-weight:600" href="index.php?r=usuario/login">ingresar</a>.'],
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
            'perfil' => 'Perfil',
            'estatus' => 'Estatus',
            'horasdisponibles' => 'Horasdisponibles',
            'codigodescuento' => 'Codigodescuento',
        ];
    }

    /**
     * Gets query for [[Codigodescuentologs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodigodescuentologs()
    {
        return $this->hasMany(Codigodescuentolog::className(), ['usuario' => 'email']);
    }

    /**
     * Gets query for [[Familiars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamiliars()
    {
        return $this->hasMany(Familiar::className(), ['usuario_id' => 'email']);
    }

    /**
     * Gets query for [[Preguntausuariorespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestas()
    {
        return $this->hasMany(Preguntausuariorespuesta::className(), ['usuario_id' => 'email']);
    }

    /**
     * Gets query for [[Preguntausuariorespuestahistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestahistoricos()
    {
        return $this->hasMany(Preguntausuariorespuestahistorico::className(), ['usuario_id' => 'email']);
    }
}
