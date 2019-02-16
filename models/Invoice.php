<?php

namespace app\models;

use Yii;
use app\controllers\phpcent\Client;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property string $nomenclature
 * @property string $amount
 * @property string $description
 * @property integer $status_id
 * @property integer $contract_id
 * @property integer $contractor_id
 * @property integer $cost_id
 * @property integer $company_id
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomenclature', 'amount', 'description', 'contractor_id', 'cost_id'], 'required'],
            [['nomenclature', 'description'], 'string'],
            [['amount'], 'number'],
            [['status_id', 'contractor_id', 'cost_id', 'company_id'], 'integer'],
            [['status_id'], 'default', 'value' => 1],
            [['company_id'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomenclature' => 'Nomenclature',
            'amount' => 'Amount',
            'description' => 'Description',
            'status_id' => 'Status ID',
            'contractor_id' => 'Contractor ID',
            'cost_id' => 'Cost ID',
            'company_id' => 'Company ID',
        ];
    }
    
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
    
    public function getContractor()
    {
    	return $this->hasOne(Contractor::className(), ['id' => 'contractor_id']);
    }
    
    public function getCompany()
    {
    	return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
    
    public function getFiles()
    {
    	return $this->hasMany(File::className(), ['invoice_id' => 'id']);
    }
    
    
    public function afterSave($insert, $changedAttributes){
    	parent::afterSave($insert, $changedAttributes);
    	$client = new Client("http://localhost:8000");
    	$client->setSecret("be297686-a482-402c-acc9-2acbca662145");
			$review_count =Invoice::find()->where(['status_id'=> 1])->count();
			$deleted_count =Invoice::find()->where(['status_id'=> 2])->count();
			$model = $this->getAttributes();
			$model["status"] = $this->status->getAttributes();			
			$model["contractor"] = $this->contractor->getAttributes();
			$model["company"] = $this->company->getAttributes();				
			if ($this->status_id == 1) {
				$model["action"] = "update";
			} else {
				$model["action"] = "delete";
			}		
			$client->publish("blabla", ["review_count" => $review_count, "deleted_count" => $deleted_count, "model" => $model]);
    }    

}
