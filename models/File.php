<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $name
 * @property integer $invoice_id
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'invoice_id'], 'required'],
            [['name'], 'string'],
            [['invoice_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'invoice_id' => 'Invoice ID',
        ];
    }
}
