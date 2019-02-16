<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Contractor;

class ContractorController extends ActiveController
{

	// указываем класс модели, который будет использоваться
	public $modelClass = 'app\models\Contractor';
	
	public function actions()
	{
		$actions = parent::actions();
	
		// настроить подготовку провайдера данных с помощью метода "prepareDataProvider()"
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
		return $actions;
	}
	
	
	public function prepareDataProvider()
	{
				return new ActiveDataProvider([
						'query' => Contractor::find()
						]);

	}

}
