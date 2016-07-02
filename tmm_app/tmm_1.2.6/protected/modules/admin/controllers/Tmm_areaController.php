<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-11-27 18:18:22 */
class Tmm_areaController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Area';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
						'Area_Area_P',
						'Area_Agent',
						'Area_Admin',
				))),
		));
	}

	/**
	 * 选择
	 */
	public function actionSelect()
	{	
		$model=new Area('search');
		$model->unsetAttributes();  // 删除默认属性
		
		if(isset($_GET['Area']))
			$model->attributes=$_GET['Area'];
		
		$this->render('select',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 添加、去除 热门城市
	 */
	public function actionHot()
	{
		if(isset($_POST['ids']) && $_POST['ids'] && isset($_POST['type']) && in_array($_POST['type'], array('yes','no')))
		{		
			$ids=$_POST['ids'];
			$type=$_POST['type'];
			if(! is_array($ids))
				$ids=array($ids);
			if(count($ids) > Yii::app()->params['admin_pageSize']) //防止一次设置数量太多
				return false;
			
			$criteria=new CDbCriteria;
			$criteria->select='id';
			//排除nane=0的市
			$criteria->addCondition(" `t`.`name`!='0' ");
			//自己pid 不等于0 自己的父 等于0
			$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');	
			$criteria->with=array('Area_Area_P'=>array('select'=>'id'));
			if($type=='yes')
				$criteria->addColumnCondition(array('t.status_hot'=>Area::status_hot_not));
			else
				$criteria->addColumnCondition(array('t.status_hot'=>Area::status_hot_yes));
			$criteria->addInCondition('`t`.`id`', $ids);
			$models=Area::model()->findAll($criteria);
			if(!empty($models))
			{
				foreach ($models as $model)
					$set_ids[]=$model->id;
				$criteria_set=new CDbCriteria;
				$criteria_set->addInCondition('id', $set_ids);
				if($type=='yes')
				{
					Area::model()->updateAll(array(
						'status_hot'=>Area::status_hot_yes,
						'admin_id'=>Yii::app()->admin->id,
						'admin_time'=>time(),
					),$criteria_set);
					$this->log('设置热门城市'.implode(',', $set_ids), ManageLog::admin,ManageLog::create);
				}else
				{
					Area::model()->updateAll(array(
						'status_hot'=>Area::status_hot_not,
						'admin_id'=>0,
						'admin_time'=>0,
					),$criteria_set);
					$this->log('去除热门城市'.implode(',', $set_ids), ManageLog::admin,ManageLog::update);
				}
			}
		}
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Area('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Area']))
			$model->attributes=$_GET['Area'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
