<?php

namespace app\controllers;


use phpDocumentor\Reflection\DocBlock\Tags\Var_;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Invoice;
use app\controllers\phpcent\Client;

class InvoiceController extends ActiveController
{
	
	// указываем класс модели, который будет использоваться
	public $modelClass = 'app\models\Invoice';
	
	public function actions()
	{
		$actions = parent::actions();
	
		// настроить подготовку провайдера данных с помощью метода "prepareDataProvider()"
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
		$actions['delete']['findModel'] = [$this, 'modelDelete'];
		return $actions;
	}
	
	public function prepareDataProvider()
	{
			$client = new Client("http://localhost:8000");
			$client->setSecret("be297686-a482-402c-acc9-2acbca662145");
			$review_count =Invoice::find()->where(['status_id'=> 1])->count();
			$deleted_count =Invoice::find()->where(['status_id'=> 2])->count();
			$client->publish("blabla", ["review_count" => $review_count, "deleted_count" => $deleted_count]);
			if (isset($_GET['status'])) {
			if ($_GET['status'] == '1') {
			return new ActiveDataProvider([
				'query' => Invoice::find()->where(['status_id'=> 1])->with('status','contractor','company')->asArray(true)
					]);
			}
			if ($_GET['status'] == '2') {
				return new ActiveDataProvider([
						'query' => Invoice::find()->where(['status_id'=> 2])->with('status','contractor','company')->asArray(true)
						]);
			}
			}
	}
	
	public function modelDelete($invoiceID)
	{
		$invoice = Invoice::findOne($invoiceID);
		$invoice->status_id = 2;
		$invoice->update();
		exit;
	}

	
}
