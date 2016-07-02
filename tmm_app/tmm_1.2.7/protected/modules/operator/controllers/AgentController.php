<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-03-31 17:40:57 */
class AgentController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Agent';
	
	/**
	 * 查看自己的信息
	 */
	public function actionOwn()
	{
		$this->render('view', array(
				'model'=>$this->loadModel(Yii::app()->operator->id, array(
					'with'=>array(
						'Agent_Admin',
						'Agent_area_id_p_Area_id' ,
						'Agent_area_id_m_Area_id',
						'Agent_area_id_c_Area_id',
						'Agent_Bank',
					),
				)),
		));
	}
	
	public function actionPwd()
	{
		$model = $this->loadModel(Yii::app()->operator->id);
				
		$model->scenario = 'pwd';
		$this->_Ajax_Verify($model,'agent-form');
		
		if (isset($_POST['Agent']))
		{
			$model->attributes = $_POST['Agent'];
			$model->password = $model::pwdEncrypt($model->new_pwd);
			if ($model->save() && $this->log('运营商修改密码', ManageLog::operator, ManageLog::update))
				$this->redirect(array('/operator/login/out'));
		}
		
		$this->render('pwd', array(
			'model'=>$model,
		));
	}
}
