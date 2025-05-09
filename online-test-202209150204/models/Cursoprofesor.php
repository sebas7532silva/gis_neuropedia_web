<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cursoprofesor".
 *
 * @property int $curso_id
 * @property string $email
 * @property string $titularidad
 *
 * @property Curso $curso
 * @property Usuario $email0
 */
class Cursoprofesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cursoprofesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_id', 'email', 'titularidad'], 'required'],
            [['curso_id'], 'integer'],
            [['email'], 'string', 'max' => 200],
            [['titularidad'], 'string', 'max' => 45],
            [['curso_id', 'email'], 'unique', 'targetAttribute' => ['curso_id', 'email']],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Curso::className(), 'targetAttribute' => ['curso_id' => 'curso_id']],
            [['email'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['email' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso_id' => 'Curso ID',
            'email' => 'Email',
            'titularidad' => 'Titularidad',
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
     * Gets query for [[Email0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmail0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'email']);
    }
}
