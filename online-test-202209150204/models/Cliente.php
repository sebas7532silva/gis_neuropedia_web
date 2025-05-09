<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $cliente_id
 * @property string $nombre
 * @property string $prim_ap
 * @property string $seg_ap
 * @property string $cony_nombre
 * @property string $cony_prim_ap
 * @property string $cony_seg_ap
 * @property string $num_int
 * @property string $vcv
 * @property string $monto_credito
 * @property string $comentarios
 * @property string $otro_estatus
 * @property string $asesor
 * @property string $gerente
 * @property string $director_hipotecario
 * @property int $estatus_cliente_id
 *
 * @property Usuario $asesor0
 * @property Usuario $directorHipotecario
 * @property EstatusCliente $estatusCliente
 * @property Usuario $gerente0
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'prim_ap'], 'required'],
            [['comentarios'], 'string'],
            [['estatus_cliente_id'], 'integer'],
            [['nombre', 'prim_ap', 'seg_ap', 'cony_nombre', 'cony_prim_ap', 'cony_seg_ap'], 'string', 'max' => 100],
            [['num_int', 'vcv', 'monto_credito', 'otro_estatus'], 'string', 'max' => 45],
            [['asesor', 'gerente', 'director_hipotecario'], 'string', 'max' => 200],
            [['asesor'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['asesor' => 'email']],
            [['director_hipotecario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['director_hipotecario' => 'email']],
            [['estatus_cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstatusCliente::className(), 'targetAttribute' => ['estatus_cliente_id' => 'estatus_cliente_id']],
            [['gerente'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['gerente' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cliente_id' => 'ID Cliente',
            'nombre' => 'Nombre',
            'prim_ap' => 'Apellido 1',
            'seg_ap' => 'Apellido 2',
            'cony_nombre' => 'Nombre Cony.',
            'cony_prim_ap' => 'Apellido 1  Cony.',
            'cony_seg_ap' => 'Apellido 2  Cony.',
            'num_int' => 'Desarrollo',
            'vcv' => 'Valor Compra Venta',
            'monto_credito' => 'Monto Credito',
            'comentarios' => 'Comentarios',
            'otro_estatus' => 'Otro Estatus',
            'asesor' => 'Asesor',
            'gerente' => 'Gerente',
            'director_hipotecario' => 'Director',
            'estatus_cliente_id' => 'Estatus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsesor0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'asesor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorHipotecario()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'director_hipotecario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstatusCliente()
    {
        return $this->hasOne(EstatusCliente::className(), ['estatus_cliente_id' => 'estatus_cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGerente0()
    {
        return $this->hasOne(Usuario::className(), ['email' => 'gerente']);
    }
}
