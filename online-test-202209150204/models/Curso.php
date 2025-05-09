<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curso".
 *
 * @property int $curso_id
 * @property string|null $titulo
 * @property string $descripcion
 * @property int $tipo_id
 * @property string|null $ubicacion
 * @property string|null $sesiones
 * @property string|null $horas
 * @property string|null $presentacion
 * @property string|null $objetivos
 * @property string|null $contenido
 * @property string|null $unidades
 * @property string|null $acreditacion
 * @property string|null $bibliografia
 *
 * @property TipoCurso $tipo
 * @property Cursoprofesor[] $cursoprofesors
 * @property Modulo[] $modulos
 */
class Curso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'tipo_id'], 'required'],
            [['descripcion', 'ubicacion', 'presentacion', 'objetivos', 'contenido', 'unidades', 'acreditacion', 'bibliografia'], 'string'],
            [['tipo_id'], 'integer'],
            [['titulo'], 'string', 'max' => 250],
            [['sesiones', 'horas'], 'string', 'max' => 10],
            [['tipo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCurso::className(), 'targetAttribute' => ['tipo_id' => 'tipo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso_id' => 'Curso ID',
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
            'tipo_id' => 'Tipo ID',
            'ubicacion' => 'Ubicacion',
            'sesiones' => 'Sesiones',
            'horas' => 'Horas',
            'presentacion' => 'Presentacion',
            'objetivos' => 'Objetivos',
            'contenido' => 'Contenido',
            'unidades' => 'Unidades',
            'acreditacion' => 'Acreditacion',
            'bibliografia' => 'Bibliografia',
        ];
    }

    /**
     * Gets query for [[Tipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(TipoCurso::className(), ['tipo_id' => 'tipo_id']);
    }

    /**
     * Gets query for [[Cursoprofesors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursoprofesors()
    {
        return $this->hasMany(Cursoprofesor::className(), ['curso_id' => 'curso_id']);
    }

    /**
     * Gets query for [[Modulos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModulos()
    {
        return $this->hasMany(Modulo::className(), ['curso_id' => 'curso_id']);
    }
}
