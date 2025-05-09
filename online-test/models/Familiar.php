<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "familiar".
 *
 * @property int $familiar_id
 * @property string $nombre
 * @property string|null $apellido
 * @property string|null $apellido2
 * @property string $fechanacimiento
 * @property int|null $semanasprematuro
 * @property string|null $genero
 * @property string|null $parentesco
 * @property string|null $usuario_id
 * @property string|null $fechaalta
 *
 * @property Actividadfamiliarhistorico[] $actividadfamiliarhistoricos
 * @property Usuario $usuario
 * @property Famililarhistorico[] $famililarhistoricos
 * @property Preguntausuariorespuesta[] $preguntausuariorespuestas
 * @property Preguntausuariorespuestahistorico[] $preguntausuariorespuestahistoricos
 */
class Familiar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'familiar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'fechanacimiento'], 'required'],
            [['fechanacimiento', 'fechaalta'], 'safe'],
            [['semanasprematuro'], 'integer'],
            [['nombre', 'apellido', 'apellido2', 'genero', 'parentesco'], 'string', 'max' => 100],
            [['usuario_id'], 'string', 'max' => 200],
			[['color'], 'string', 'max' => 45],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'familiar_id' => 'Familiar ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'apellido2' => 'Apellido2',
            'fechanacimiento' => 'Fecha Nacimiento',
            'semanasprematuro' => 'Semanasprematuro',
            'genero' => 'Genero',
            'parentesco' => 'Parentesco',
            'usuario_id' => 'Usuario ID',
            'fechaalta' => 'Fechaalta',
			'color' => 'Color',
        ];
    }

    /**
     * Gets query for [[Actividadfamiliarhistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividadfamiliarhistoricos()
    {
        return $this->hasMany(Actividadfamiliarhistorico::className(), ['familiar_id' => 'familiar_id']);
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

    /**
     * Gets query for [[Famililarhistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFamililarhistoricos()
    {
        return $this->hasMany(Famililarhistorico::className(), ['familiar_id' => 'familiar_id']);
    }

    /**
     * Gets query for [[Preguntausuariorespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestas()
    {
        return $this->hasMany(Preguntausuariorespuesta::className(), ['familiar_id' => 'familiar_id']);
    }

    /**
     * Gets query for [[Preguntausuariorespuestahistoricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntausuariorespuestahistoricos()
    {
        return $this->hasMany(Preguntausuariorespuestahistorico::className(), ['familiar_id' => 'familiar_id']);
    }
}
