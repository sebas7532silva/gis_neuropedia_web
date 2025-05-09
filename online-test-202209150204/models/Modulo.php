<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modulo".
 *
 * @property int $modulo_id
 * @property string|null $titulo
 * @property string $video
 * @property int|null $curso_id
 * @property string|null $ejercicios
 * @property string|null $horas_practicas
 * @property string|null $horas_teoricas
 * @property string $usuario_id
 *
 * @property Curso $curso
 * @property Modulomaterial $modulomaterial
 * @property Moduloprofesor[] $moduloprofesors
 * @property Usuario[] $emails
 */
class Modulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video', 'usuario_id'], 'required'],
            [['curso_id'], 'integer'],
            [['ejercicios'], 'string'],
            [['titulo'], 'string', 'max' => 250],
            [['video'], 'string', 'max' => 500],
            [['horas_practicas', 'horas_teoricas'], 'string', 'max' => 45],
            [['usuario_id'], 'string', 'max' => 200],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Curso::className(), 'targetAttribute' => ['curso_id' => 'curso_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'modulo_id' => 'Modulo ID',
            'titulo' => 'Titulo',
            'video' => 'Video',
            'curso_id' => 'Curso ID',
            'ejercicios' => 'Ejercicios',
            'horas_practicas' => 'Horas Practicas',
            'horas_teoricas' => 'Horas Teoricas',
            'usuario_id' => 'Usuario ID',
        ];
    }

    /**
     * Gets query for [[Curso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(Curso::className(), ['curso_id' => 'curso_id']);
    }

    /**
     * Gets query for [[Modulomaterial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModulomaterial()
    {
        return $this->hasOne(Modulomaterial::className(), ['modulo_id' => 'modulo_id']);
    }

    /**
     * Gets query for [[Moduloprofesors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModuloprofesors()
    {
        return $this->hasMany(Moduloprofesor::className(), ['modulo_id' => 'modulo_id']);
    }

    /**
     * Gets query for [[Emails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(Usuario::className(), ['email' => 'email'])->viaTable('moduloprofesor', ['modulo_id' => 'modulo_id']);
    }
}
