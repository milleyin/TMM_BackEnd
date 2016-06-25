<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-07 10:35:45 */
class WifiController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Wifi';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new Wifi('operatorSearch');
		$model->unsetAttributes();  // 删除默认属性
		if (isset($_GET['Wifi']))
			$model->attributes = $_GET['Wifi'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 保存选中的酒店服务
	 * @param unknown $id
	 */
	public function actionSave($id)
	{
		if (isset($_POST['wifi_ids'], $_POST['type']) && $_POST['wifi_ids'])
		{
			$type = $_POST['type'];
			if ( !is_array($_POST['wifi_ids']))
				$_POST['wifi_ids'] = array($_POST['wifi_ids']);
			//加载项目住
			$model = $this->loadHotel($id);
			//安全过滤tags id
			$wifi_ids = Wifi::filter_wifi($_POST['wifi_ids']);
			if ($type == 'yes')
			{
				//过滤之前有的
				$wifi_ids_save = ItemsWifi::not_select_wifi($wifi_ids, $model->id);
				//保存
				$return = ItemsWifi::select_wifi_save($wifi_ids_save, $model->Hotel_Items);
			}
			else
				$return = ItemsWifi::select_wifi_delete($wifi_ids, $model->id);
			if ($return)
			{
				if ($type == 'yes')
					$this->log('添加项目(住)酒店服务', ManageLog::operator, ManageLog::create);
				else
					$this->log('去除项目(住)酒店服务', ManageLog::operator, ManageLog::clear);
				echo 1;
			}
			else
				echo '操作过于频繁，请刷新页面从新选择！';
		}
		else
			echo '没有选中酒店服务，请重新选择！';
	}
	
	/**
	 * 选择酒店服务
	 * @param integer $id
	 */
	public function actionSelect($id)
	{
		$model = new Wifi('operatorSearch');
		// 删除默认属性
		$model->unsetAttributes();
		if (isset($_GET['Wifi']))
			$model->attributes = $_GET['Wifi'];
		//选择的酒店服务 只能是上线的
		$model->status = 1;
				
		$this->render('select', array(
				'model'=>$model,
				'select'=>$this->loadHotel($id),
		));
	}
	
	/**
	 * 加载项目住
	 * @param unknown $id
	 * @return unknown
	 */
	public function loadHotel($id)
	{
		//设置加载数据模型名
		$this->_class_model = 'Hotel';
		return $this->loadModel($id, array(
				'with'=>array('Hotel_Items'),
				'condition'=>'Hotel_Items.status=:status AND Hotel_Items.audit!=:audit AND Hotel_Items.agent_id=:agent_id',
				'params'=>array(':status'=>Items::status_offline, ':audit'=>Items::audit_pending, ':agent_id'=>Yii::app()->operator->id),
		));
	}
}
