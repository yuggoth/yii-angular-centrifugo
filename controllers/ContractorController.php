<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Contractor;

class ContractorController extends ActiveController
{

	// ��������� ����� ������, ������� ����� ��������������
	public $modelClass = 'app\models\Contractor';
	
	public function actions()
	{
		$actions = parent::actions();
	
		// ��������� ���������� ���������� ������ � ������� ������ "prepareDataProvider()"
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
