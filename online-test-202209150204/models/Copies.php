<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "copies".
 *
 * @property string $copy_id
 * @property string|null $copy
 */
class Copies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'copies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['copy_id'], 'required'],
            [['copy'], 'string'],
            [['copy_id'], 'string', 'max' => 100],
            [['copy_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'copy_id' => 'Copy ID',
            'copy' => 'Copy',
        ];
    }
}
