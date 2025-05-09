<?php

namespace app\models;
use yii\web\UploadedFile;


use Yii;

/**
 * This is the model class for table "recurso".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $archivo
 */
class Recurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $archivox;


    public static function tableName()
    {
        return 'recurso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'archivo'], 'string', 'max' => 500],
            [['archivox'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx, xls, xlsx'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'archivo' => 'Archivo',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->archivox->saveAs(Yii::getAlias('@webroot') . "/files/".$this->archivo);
            return true;
        } else {
            return false;
        }
    }

}
