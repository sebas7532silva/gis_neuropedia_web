<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_curso".
 *
 * @property int $tipo_id
 * @property string $nombre
 * @property int $horas
 *
 * @property Curso[] $cursos
 */
class TipoCurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'horas'], 'required'],
            [['horas'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipo_id' => 'Tipo ID',
            'nombre' => 'Nombre',
            'horas' => 'Horas',
        ];
    }

    /**
     * Gets query for [[Cursos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursos()
    {
        return $this->hasMany(Curso::className(), ['tipo_id' => 'tipo_id']);
    }
}
