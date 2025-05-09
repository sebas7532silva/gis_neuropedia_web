<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codigodescuentousuario".
 *
 * @property int $codigodescuentousuario_id
 * @property string|null $usuario
 * @property string|null $fechaenvio
 * @property string|null $tipo
 * @property int|null $porcentaje
 * @property string|null $fechainicio
 * @property string|null $fechafin
 * @property int|null $pagototal
 * @property string|null $estatus
 * @property string|null $codigo
 */
class Codigodescuentousuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codigodescuentousuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechaenvio', 'fechainicio', 'fechafin'], 'safe'],
            [['porcentaje', 'pagototal'], 'integer'],
            [['usuario', 'codigo'], 'string', 'max' => 200],
            [['tipo', 'estatus'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigodescuentousuario_id' => 'Codigodescuentousuario ID',
            'usuario' => 'Usuario',
            'fechaenvio' => 'Fechaenvio',
            'tipo' => 'Tipo',
            'porcentaje' => 'Porcentaje',
            'fechainicio' => 'Fechainicio',
            'fechafin' => 'Fechafin',
            'pagototal' => 'Pagototal',
            'estatus' => 'Estatus',
            'codigo' => 'Codigo',
        ];
    }
}
