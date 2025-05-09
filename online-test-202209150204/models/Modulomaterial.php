<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "modulomaterial".
 *
 * @property int $modulo_id
 * @property string $material
 *
 * @property Modulo $modulo
 */
class Modulomaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $archivo;

    public static function tableName()
    {
        return 'modulomaterial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modulo_id', 'material'], 'required'],
            [['modulo_id'], 'integer'],
            [['material'], 'string', 'max' => 45],
            [['modulo_id'], 'unique'],
            [['modulo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modulo::className(), 'targetAttribute' => ['modulo_id' => 'modulo_id']],
            [['archivo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx, xls, xlsx'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'modulo_id' => 'Modulo ID',
            'material' => 'Material',
        ];
    }

    /**
     * Gets query for [[Modulo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModulo()
    {
        return $this->hasOne(Modulo::className(), ['modulo_id' => 'modulo_id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->archivo->saveAs(Yii::getAlias('@webroot') . "/uploads/".$this->filename);
            return true;
        } else {
            return false;
        }
    }

}
