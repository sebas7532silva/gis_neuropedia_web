<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "examen".
 *
 * @property int $examen_id
 * @property string $titulo
 * @property string $descripcion
 */
class Examen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'examen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion'], 'required'],
            [['descripcion'], 'string'],
            [['titulo'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'examen_id' => 'Examen ID',
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
        ];
    }
}
