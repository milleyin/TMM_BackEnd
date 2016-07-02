<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-10 10:32:06 */
class Agent_shopsController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Shops';

	/**
	 * 修改点,线标签
	 */
	public function actionUpdate_2($id)
	{
		//CSS
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

		$shops_classliys=$this->shops_type();
		$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
		$condition=$dot_or_thrand['condition'];
		$params=$dot_or_thrand['params'];

		//查询当前线路的信息(点，线)
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Shops_ShopsClassliy'
			),
			'condition'=>$condition.' AND `t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
			'params'=>array_merge($params,array(':audit'=>Shops::audit_draft,':agent_id'=>Yii::app()->agent->id)),
		));
		//查询当前线路的信息(点，线) 
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Shops_ShopsClassliy',
				'Shops_'.$model->Shops_ShopsClassliy->append,
			),
			'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
			'params'=>array(':audit'=>Shops::audit_draft,':agent_id'=>Yii::app()->agent->id),
		));
		if(isset($_POST['Shops']['id']) && $_POST['Shops']['id'] ==$id)
		{
			if($model->updateByPk($id,array('audit'=>Shops::audit_pending)))
			{
				$this->log('提交项目('.$model->Shops_ShopsClassliy->name.')审核',ManageLog::agent,ManageLog::update);

				$this->redirect(array('update_3','id'=>$model->id));
			}
		}

		$TagsElement='tags_shops_'.$model->Shops_ShopsClassliy->admin;

		$model->Shops_TagsElement=TagsElement::get_select_tags(constant('TagsElement::'.$TagsElement),$id);

		$tags_model=new Tags;

		$this->render('update_2',array(
			'model'=>$model,
			'tags_model'=>$tags_model->select_tags_element(true),
		));

	}


	/**
	 * 标签选中
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id)
	{
		if(isset($_POST['tag_ids']) && !is_array($_POST['tag_ids']) && $_POST['tag_ids'] !=='' && isset($_POST['type']))
		{
			$type=$_POST['type'];
			$shops_classliys=$this->shops_type();
			$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
			$condition=$dot_or_thrand['condition'];
			$params=$dot_or_thrand['params'];

			$model= $this->loadModel($id,array(
				'with'=>array(
					'Shops_ShopsClassliy'
				),
				'condition'=>$condition.'AND `t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
				'params'=>array_merge($params,array(':audit'=>Shops::audit_draft,':agent_id'=>Yii::app()->agent->id)),
			));
			$TagsElement='tags_shops_'.$model->Shops_ShopsClassliy->admin;

			$tags_id=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){

				$model->Shops_TagsElement=TagsElement::get_select_tags(constant('TagsElement::'.$TagsElement),$id);
				if(count($model->Shops_TagsElement)>=Yii::app()->params['tags']['shops']['select'])
				{
					echo Yii::app()->params['tags']['shops']['error'];
					Yii::app()->end();
				}
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_id,$id,constant('TagsElement::'.$TagsElement));
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id,constant('TagsElement::'.$TagsElement),TagsElement::agent);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_id, $id, constant('TagsElement::'.$TagsElement));

			if($return)
			{
				if($type=='yes')
					$this->log('线路('.$model->Shops_ShopsClassliy->name.')添加一个标签', ManageLog::agent,ManageLog::create);
				else
					$this->log('线路('.$model->Shops_ShopsClassliy->name.')去除一个标签', ManageLog::agent,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}

	/**
	 * 获取当前的线路类型
	 * @param unknown $c_id
	 * @param string $shops_classliys
	 * @return Ambigous <Ambigous, static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function actionUpdate_3($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

		$shops_classliys=$this->shops_type();
		$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
		$condition=$dot_or_thrand['condition'];
		$params=$dot_or_thrand['params'];

		$model = $this->loadModel($id, array(
			'with' => array(
				'Shops_ShopsClassliy'
			),
			'condition' => $condition.'AND `t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
			'params' => array_merge($params,array(':audit' => Items::audit_pending, ':agent_id' => Yii::app()->agent->id)),
		));
		//成功的页面
		$this->render('update_3', array(
			'model' => $model,
		));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		$shops_classliys=$this->shops_type();
		$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
		$condition=$dot_or_thrand['condition'];
		$params=$dot_or_thrand['params'];

		$condition .= ' AND `status`=:status AND `agent_id`=:agent_id';
		$params[':status']=Shops::status_offline;
		$params[':agent_id']=Yii::app()->agent->id;

		$this->_class_model='Shops';
		$model=$this->loadModel($id,$condition,$params);
		$name=$this->get_classliy($model->c_id,$shops_classliys)->name;

		if($model->updateByPk($id,array('status'=>Shops::status_del)))
			$this->log('下线线路('.$name.')',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		$shops_classliys=$this->shops_type();
		$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
		$condition=$dot_or_thrand['condition'];
		$params=$dot_or_thrand['params'];

		$condition .= ' AND `status`=:status AND `agent_id`=:agent_id';
		$params[':status']=Shops::status_online;
		$params[':agent_id']=Yii::app()->agent->id;

		$this->_class_model='Shops';
		$model=$this->loadModel($id,$condition,$params);
		$name=$this->get_classliy($model->c_id,$shops_classliys)->name;

		if($model->updateByPk($id,array('status'=>Shops::status_offline)))
			$this->log('下线线路('.$name.')',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		$shops_classliys=$this->shops_type();
		$dot_or_thrand=$this->dot_or_thrand($shops_classliys);
		$condition=$dot_or_thrand['condition'];
		$params=$dot_or_thrand['params'];

		$condition .= ' AND `status`=:status AND `agent_id`=:agent_id AND `list_info` !=\'\'';
		$params[':status']=Shops::status_offline;
		$params[':agent_id']=Yii::app()->agent->id;

		$this->_class_model='Shops';
		$model=$this->loadModel($id,$condition,$params);
		$name=$this->get_classliy($model->c_id,$shops_classliys)->name;

		if($model->updateByPk($id,array('status'=>Shops::status_online)))
			$this->log('上线线路('.$name.')',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 获取点线的类型模型
	 * @param unknown $shops_types
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function shops_type($shops_types=array('Dot','Thrand'))
	{
		$old_model=$this->_class_model;
		foreach ($shops_types as $shops_type)
		{
			$this->_class_model=$shops_type;
			$shops_classliys[]=ShopsClassliy::getClass();
		}
		$this->_class_model=$old_model;
		return $shops_classliys;
	}

	/**
	 * 点线 的条件
	 * @param unknown $shops_classliys
	 * @param string $relation
	 * @return multitype:string multitype:NULL
	 */
	public function dot_or_thrand($shops_classliys='',$relation='`t`.')
	{
		if($shops_classliys=='')
			$shops_classliys=$this->shops_type();
		$params=array();
		$condition='(';
		foreach ($shops_classliys as $key=>$shops_classliy)
		{
			if($condition != '(')
				$or=' OR ';
			else
				$or='';
			$condition.=$or.$relation.'`c_id`=:'.$key.'_c_id';
			$params[':'.$key.'_c_id']=$shops_classliy->id;
		}
		return array('condition'=>$condition.') ','params'=>$params);
	}

	/**
	 * 获取当前的线路类型
	 * @param unknown $c_id
	 * @param string $shops_classliys
	 * @return Ambigous <Ambigous, static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function get_classliy($c_id,$shops_classliys='')
	{
		if($shops_classliys=='')
			$shops_classliys=$this->shops_type();
		foreach ($shops_classliys as $shops_classliy)
		{
			if($shops_classliy->id===$c_id)
				return $shops_classliy;
		}
		return new ShopsClassliy;
	}

}
