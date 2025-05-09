<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntausuariorespuestahistorico".
 *
 * @property int $respuestahistorico_id
 * @property int|null $pregunta_id
 * @property string|null $usuario_id
 * @property int|null $familiar_id
 * @property string|null $respuesta
 * @property string|null $comentario
 * @property string|null $fecha
 *
 * @property Familiar $familiar
 * @property Examenpregunta $pregunta
 * @property Usuario $usuario
 */
class Preguntausuariorespuestahistorico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preguntausuariorespuestahistorico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta_id', 'familiar_id'], 'integer'],
            [['comentario'], 'string'],
            [['fecha'], 'safe'],
            [['usuario_id'], 'string', 'max' => 200],
            [['respuesta'], 'string', 'max' => 500],
            [['familiar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Familiar::className(), 'targetAttribute' => ['familiar_id' => 'familiar_id']],
            [['pregunta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Examenpregunta::className(), 'targetAttribute' => ['pregunta_id' => 'pregunta_id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'respuestahistorico_id' => 'Respuestahistorico ID',
            'pregunta_id' => 'Pregunta ID',
            'usuario_id' => 'Usuario ID',
            'familiar_id' => 'Familiar ID',
            'respuesta' => 'Respuesta',
            'comentario' => 'Comentario',
            'fecha' => 'Fecha',
        ];
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

    /**
     * Gets query for [[Pregunta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPregunta()
    {
        return $this->hasOne(Examenpregunta::className(), ['pregunta_id' => 'pregunta_id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'usuario_id']);
    }
}
