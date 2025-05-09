<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "famililarhistorico".
 *
 * @property int $historico_id
 * @property int|null $familiar_id
 * @property int|null $competencia_id
 * @property int|null $edad_id
 * @property float|null $resultado
 * @property string|null $fecha
 * @property int|null $revision
 *
 * @property Competencia $competencia
 * @property Edad $edad
 * @property Familiar $familiar
 */
class Famililarhistorico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'famililarhistorico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['familiar_id', 'competencia_id', 'edad_id', 'revision'], 'integer'],
            [['resultado'], 'number'],
            [['fecha'], 'safe'],
            [['competencia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Competencia::className(), 'targetAttribute' => ['competencia_id' => 'competencia_id']],
            [['edad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edad::className(), 'targetAttribute' => ['edad_id' => 'edad_id']],
            [['familiar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Familiar::className(), 'targetAttribute' => ['familiar_id' => 'familiar_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'historico_id' => 'Historico ID',
            'familiar_id' => 'Familiar ID',
            'competencia_id' => 'Competencia ID',
            'edad_id' => 'Edad ID',
            'resultado' => 'Resultado',
            'fecha' => 'Fecha',
            'revision' => 'Revision',
        ];
    }

    /**
     * Gets query for [[Competencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencia()
    {
        return $this->hasOne(Competencia::className(), ['competencia_id' => 'competencia_id']);
    }

    /**
     * Gets query for [[Edad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdad()
    {
        return $this->hasOne(Edad::className(), ['edad_id' => 'edad_id']);
    }

    /**
     * Gets query for [[Familiar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamiliar()
    {
        return $this->hasOne(Familiar::className(), ['familiar_id' => 'familiar_id']);
    }
}
