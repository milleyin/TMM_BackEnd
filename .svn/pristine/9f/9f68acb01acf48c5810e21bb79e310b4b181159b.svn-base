<?php

class Agent_homeController extends AgentController
{
	public function actionIndex()
	{
		$this->layout='/layouts/_column';
		$this->frame=array(
			'left'=>array(
				'url'=>'/agent/agent_home/left',
			),
			'right'=>array(
				'url'=>'/agent/agent_home/right',
			)
		);
		$this->render('index',array('content'=>'您的浏览器不支持框架,请更换相应的浏览器'));
	}

	public function actionLeft(){
		$this->layout='/layouts/column_left';
		$this->addJs(Yii::app()->baseUrl.'/css/agent/script/functions.js');
		$this->render('left');
	}

	public function actionRight()
	{
		$this->layout='/layouts/column_right';
		$this->addCss(Yii::app()->baseUrl.'/css/agent/css/home.css');
		$this->addJs(Yii::app()->baseUrl.'/css/agent/script/highcharts/highcharts.js');
		$this->addJs(Yii::app()->baseUrl.'/css/agent/script/highcharts/modules/exporting.js');
			
		$data=array(
			'income'=>array(
				'收益线'=>array(
						0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
				),
			),
			'store_order'=>array(
				'公司名1'=>array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
				'公司名2'=>array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
				'公司名3'=>array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
				'公司名4'=>array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
				'公司名5'=>array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
			),
		);
		$this->_class_model='Agent';
		$model=$this->loadModel(Yii::app()->agent->id);
		$audit_pending=array(
			'items'=>Items::model()->count(
					'`agent_id`=:agent_id AND `status`=0 AND `audit`=:audit',
					array(':agent_id'=>Yii::app()->agent->id,':audit'=>Items::audit_pending)
			),
			'dot'=>Dot::model()->count(array(
				'with'=>array('Dot_Shops'),
				'condition'=>'`Dot_Shops`.`agent_id`=:agent_id AND `Dot_Shops`.`status`=0 AND `Dot_Shops`.`audit`=:audit',
				'params'=>array(':agent_id'=>Yii::app()->agent->id,':audit'=>Shops::audit_pending)
			)),
			'thrand'=>Thrand::model()->count(array(
				'with'=>array('Thrand_Shops'),
				'condition'=>'`Thrand_Shops`.`agent_id`=:agent_id AND `Thrand_Shops`.`status`=0 AND `Thrand_Shops`.`audit`=:audit',
				'params'=>array(':agent_id'=>Yii::app()->agent->id,':audit'=>Shops::audit_pending)
			)),
		);
		//收益
		$income=0;
		$this->render('right',array('data'=>$data,'model'=>$model,'audit_pending'=>$audit_pending,'income'=>$income));
	}

	/**
	 * 根据id  省市区联动
	 */
	public function actionArea(){
		Area::action();
	}
	
	/**
	 * 根据名字 省市区联动
	 */
	public function actionArea_name(){
		Area::action_name();
	}

}