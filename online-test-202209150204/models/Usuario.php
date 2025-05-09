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
 * @property string|null $curriculo
 * @property string $perfil
 * @property string $estatus
 * @property int|null $horasdisponibles
 *
 * @property Cursoprofesor[] $cursoprofesors
 * @property Curso[] $cursos
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
            [['curriculo'], 'string'],
            [['horasdisponibles'], 'integer'],
            [['email'], 'string', 'max' => 200],
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
            'curriculo' => 'Curriculo',
            'perfil' => 'Perfil',
            'estatus' => 'Estatus',
            'horasdisponibles' => 'Horasdisponibles',
        ];
    }

    /**
     * Gets query for [[Cursoprofesors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursoprofesors()
    {
        return $this->hasMany(Cursoprofesor::className(), ['email' => 'email']);
    }

    /**
     * Gets query for [[Cursos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursos()
    {
        return $this->hasMany(Curso::className(), ['curso_id' => 'curso_id'])->viaTable('cursoprofesor', ['email' => 'email']);
    }
}
