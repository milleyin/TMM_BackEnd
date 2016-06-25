<?php
/**
 * 结伴游
 * Class Agent_groupController
 */
class Agent_groupController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Group';

	/**
	 * 查看详情
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Group_ShopsClassliy',
				'Group_Shops'=>array('with'=>array('Shops_Agent')),
				'Group_User',
				'Group_Pro'=>array('with'=>array(
					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
					'Pro_Items'=>array('with'=>array(
						'Items_area_id_p_Area_id',
						'Items_area_id_m_Area_id',
						'Items_area_id_c_Area_id',
						'Items_ItemsImg',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_ItemsClassliy',
					)),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				)),
			),
			'condition'=>'`t`.`c_id`=:c_id AND `Group_Shops`.`agent_id`=:agent_id AND `Group_Shops`.`status`>:status',
			'params'=>array(':c_id'=>$shops_classliy->id,':agent_id'=>Yii::app()->agent->id,':status'=>Shops::status_del),
			'order'=>'`Group_Pro`.`day_sort`,`Group_Pro`.`half_sort`,`Group_Pro`.`sort`',
		));
		$model->Group_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_group,$id);

		$this->render('view',array('model'=>$model));
	}

	/**
	 * 搜索结伴游名称
	 * @param unknown $criteria
	 */
	public function search_info($criteria)
	{
		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('(`Group_Shops`.`name` LIKE :search_info)');
		}
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');

		$model=new Group;
		$shops_classliy=ShopsClassliy::getClass();
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Group_ShopsClassliy',
			'Group_Shops'=>array('with'=>array('Shops_Agent')),
			'Group_Pro'=>array(
				'with'=>array(
					'Pro_Group_Dot'=>array(
							'with'=>array(	
								'Dot_Shops'=>array(
// 									'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit',
// 									'params'=>array(':audit'=>Shops::audit_pass),
								),
							)
					),
					'Pro_Group_Thrand'=>array(
							'with'=>array(									
								'Thrand_Shops'=>array(
// 									'condition'=>'`Thrand_Shops`.`status`=1 AND `Thrand_Shops`.`audit`=:audit',
// 									'params'=>array(':audit'=>Shops::audit_pass),
								),
							),
					),
					'Pro_Items'=>array(
							'with'=>array(
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
								'Items_ItemsImg',
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Store_Manager',
								'Items_ItemsClassliy',
							),
// 							'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit AND `Pro_Items`.`agent_id`=:agent_id',
// 							'params'=>array(':audit'=>Items::audit_pass,':agent_id'=>Yii::app()->agent->id),
					),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				),
				'order'=>'`Group_Pro`.`day_sort`,`Group_Pro`.`half_sort`,`Group_Pro`.`sort`',
			),
		);

		$criteria->addColumnCondition(array(
			'`t`.`c_id`'=>$shops_classliy->id,
			'`Group_Shops`.`agent_id`'=>0,
			'`Group_Shops`.`status`'=>Shops::status_online,
		));
		// 搜索
		if (isset($_GET['search_status']) && !empty($_GET['search_status']))
		{
			$status_array=array(
				Group::group_cancel,Group::group_peering,Group::group_user_confirm,
				Group::group_store_confirm,Group::group_already_peer,Group::group_no_peer
			);
			$group_status = trim($_GET['search_status']);
			if (in_array($group_status,$status_array))
				$criteria->addColumnCondition(array('`t`.`group`'=>$group_status));
		}
		$this->search_info($criteria);
		$data = new CActiveDataProvider('Group', array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
				'defaultOrder'=>'`Group_Shops`.`up_time` desc', //设置默认排序
			)));

		$this->render('admin',array(
			'model'=>$data,
		));
	}


}
