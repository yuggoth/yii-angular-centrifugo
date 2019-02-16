<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\File;

class FileController extends ActiveController
{
	
	// указываем класс модели, который будет использоваться
	public $modelClass = 'app\models\File';
	public $enableCsrfValidation = false;
	
	public function actions()
	{
		$actions = parent::actions();
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
		$actions['delete']['findModel'] = [$this, 'modelDelete'];
		unset($actions['create']);
		return $actions;
	}	
	
	public function prepareDataProvider()
	{
			if (isset($_GET['invoice_id'])) {
				return new ActiveDataProvider([
						'query' => File::find()->where(['invoice_id'=> $_GET['invoice_id']])
						]);
			}
	}
	
	public function actionCreate()
    {
        	if ( !empty( $_FILES ) ) {
        		if($_FILES['file']['size'] < 10000000){
    			$tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
    			$extension = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
    			$file_name = uniqid();
    			$uploadPath = '../uploads' . DIRECTORY_SEPARATOR . $file_name . '.' . $extension;   			
    			move_uploaded_file( $tempPath, $uploadPath );
    			$file = new \app\models\File;
    			$file->name = $file_name . '.' . $extension;
    			$file->invoice_id = $_REQUEST["invoice_id"];
    			$file->save();
    			$answer = array( 'id' => $file->id , 'name' => $file->name);
    			exit(json_encode($answer));
        		}
    		} else {
    			echo 'No files';
    		}
    }
    
    public function modelDelete($invoiceID)
    {
    	if($invoiceID) {
    		$file= File::findOne($invoiceID);
    		unlink('../uploads' . DIRECTORY_SEPARATOR . $file->name);
    		$file->delete();
    	}
    
    	exit;
    }



}
