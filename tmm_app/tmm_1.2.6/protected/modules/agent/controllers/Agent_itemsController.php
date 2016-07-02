<?php
/**
 * 项目
 * Class Agent_itemsController
 */
class Agent_itemsController extends AgentController
{
    /**
     * 默认操作数据模型
     * @var string
     */
    public $_class_model='Items';
	
    /**
     * 统计
     * @return multitype:string Ambigous <string, mixed, unknown>
     */
    public function items_count()
    {
    	$array=array();
        $criteria_items=new CDbCriteria;
        $criteria_items->addColumnCondition(array(
            'agent_id'=>Yii::app()->agent->id
        ));
        $criteria_items->compare('audit','<>'.Items::audit_draft);
        $criteria_items->compare('status','>'.Items::status_del);
        $array['items']=Items::model()->count($criteria_items);

    	$criteria_nopass=new CDbCriteria;
    	$criteria_nopass->addColumnCondition(array(
    		'agent_id'=>Yii::app()->agent->id,
    		'status'=>Items::status_offline,
    		'audit'=>Items::audit_nopass,		
    	));
    	$array['nopass']=Items::model()->count($criteria_nopass);

    	$criteria_draft=new CDbCriteria;
    	$criteria_draft->addColumnCondition(array(
    			'agent_id'=>Yii::app()->agent->id,
    			'status'=>Items::status_offline,
    			'audit'=>Items::audit_draft,
    	));
    	$array['draft']=Items::model()->count($criteria_draft);
    	return $array;
    }

    /**
     * 搜索商家名称 项目名称
     * @param unknown $criteria
     */
   	public function search_info($criteria)
   	{
   		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
   		{
   			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
   			$criteria->addCondition('(`t`.`name` LIKE :search_info OR `Items_StoreContent`.`name` LIKE :search_info)');
   		}   		 
   	}
    
    /**
     *管理页--我的项目
     */
    public function actionAdmin()
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');

        $criteria=new CDbCriteria;
        $criteria->with=array(
            'Items_agent',
            'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
            'Items_Store_Manager'=>array('select'=>'phone'),
            'Items_ItemsClassliy',
            'Items_area_id_p_Area_id'=>array('select'=>'name'),
            'Items_area_id_m_Area_id'=>array('select'=>'name'),
            'Items_area_id_c_Area_id'=>array('select'=>'name'),
            'Items_ItemsImg'=>array('order'=>'rand()'),
            'Items_Fare',
        );
		$criteria->addColumnCondition(array(
			't.agent_id'=>Yii::app()->agent->id,
		));
		$criteria->compare('`t`.`audit`','<>'.Items::audit_draft);
		$criteria->compare('`t`.`status`','>'.Items::status_del);
        // 搜索
       	if (isset($_GET['search_status']) )
       	{
       		$status_array=array(Items::status_offline,Items::status_online);
            $status = trim($_GET['search_status']);
            if (in_array($status,$status_array))
            	$criteria->addColumnCondition(array('`t`.`status`'=>$status));
        }
		$this->search_info($criteria);
        $data = new CActiveDataProvider('Items', array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>Yii::app()->params['admin_pageSize'],
                ),
                'sort'=>array(
                    'defaultOrder'=>'t.add_time desc', //设置默认排序
            )));

        $this->render('admin',array(
            'model'=>$data,
        	'count'=>$this->items_count(),
        ));

    }

    /**
     *管理页-我的项目-审核未通过
     */
    public function actionAdmin_no_pass()
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');

        $criteria=new CDbCriteria;
        $criteria->with=array(
            'Items_agent',
            'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
            'Items_Store_Manager'=>array('select'=>'phone'),
            'Items_ItemsClassliy',
            'Items_area_id_p_Area_id'=>array('select'=>'name'),
            'Items_area_id_m_Area_id'=>array('select'=>'name'),
            'Items_area_id_c_Area_id'=>array('select'=>'name'),
            'Items_ItemsImg'=>array('order'=>'rand()'),
            'Items_Fare'
        );
        $criteria->addColumnCondition(array(
            't.agent_id'=>Yii::app()->agent->id,
        ));
        $criteria->compare('`t`.`audit`','='.Items::audit_nopass);
        $criteria->compare('`t`.`status`','='.Items::status_offline);
        
       	$this->search_info($criteria);
       
        $data = new CActiveDataProvider('Items', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['admin_pageSize'],
            ),
            'sort'=>array(
                'defaultOrder'=>'t.add_time desc', //设置默认排序
            )));

        $this->render('admin_no_pass',array(
            'model'=>$data,
            'count'=>$this->items_count(),
        ));

    }

    /**
     *管理页-我的项目-草稿
     */
    public function actionAdmin_draft()
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');

        $criteria=new CDbCriteria;
        $criteria->with=array(
            'Items_agent',
            'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
            'Items_Store_Manager'=>array('select'=>'phone'),
            'Items_ItemsClassliy',
            'Items_area_id_p_Area_id'=>array('select'=>'name'),
            'Items_area_id_m_Area_id'=>array('select'=>'name'),
            'Items_area_id_c_Area_id'=>array('select'=>'name'),
            'Items_ItemsImg'=>array('order'=>'rand()'),
            'Items_Fare'
        );
        $criteria->addColumnCondition(array(
            't.agent_id'=>Yii::app()->agent->id,
        ));
        $criteria->compare('`t`.`audit`','='.Items::audit_draft);
        $criteria->compare('`t`.`status`','='.Items::status_offline);

        $this->search_info($criteria);
        $data = new CActiveDataProvider('Items', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['admin_pageSize'],
            ),
            'sort'=>array(
                'defaultOrder'=>'t.add_time desc', //设置默认排序
            )
       ));

        $this->render('admin_draft',array(
            'model'=>$data,
            'count'=>$this->items_count(),
        ));
    }
    
    /**
     * 返回商家信息
     */
    public function actionInfo()
    {
    	if(isset($_POST['id']) && $_POST['id'])
    	{
    		$this->_class_model='StoreUser';
    		$model=$this->loadModel($_POST['id'],array(
	    			'with'=>array(
	    				'Store_Content'=>array('with'=>array('Content_Stoer_Son')),
	    			),
    				'condition'=>'`t`.`status`=1 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
    				'params'=>array(':agent_id'=>Yii::app()->agent->id),
    		));
    		echo $this->renderPartial('_info',array(
    				'model'=>$model
    		), true);
    		Yii::app()->end();		
    	}	
    }

    /**
     * 选择商家创建
     */
    public function actionSelect()
    {
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
        $model=new StoreUser;
        
        if (isset($_POST['id']))
        {        
        	$this->_class_model='StoreUser';
			$store=$this->loadModel($_POST['id'],'`status`=1 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id));	
            $this->redirect(array('/agent/agent_eat/create','id'=>$store->id));
        }
        $this->render('select',array(
            'model'=>$model,
        ));
    }

    /**
     * 创建第三部 修改 标签页
     * @param unknown $id
     */
    public function actionCreate_3($id)
    {
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
    	
		$model=$this->loadModel($id,array(
				'with'=>array(
					'Items_ItemsClassliy'
				),
				'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
				'params'=>array(':audit'=>Items::audit_draft,':agent_id'=>Yii::app()->agent->id),
		));
		$model=$this->loadModel($id,array(
				'with'=>array(
						'Items_ItemsClassliy',
						'Items_'.$model->Items_ItemsClassliy->append,
				),
				'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
				'params'=>array(':audit'=>Items::audit_draft,':agent_id'=>Yii::app()->agent->id),
		));
		
		if(isset($_POST['Items']['id']) && $_POST['Items']['id']==$id)
		{
			if($model->updateByPk($id,array('audit'=>Items::audit_pending)))
			{
				$this->log('提交项目('.$model->Items_ItemsClassliy->name.')审核',ManageLog::agent,ManageLog::update);
				if($model->add_time==$model->up_time)
					$this->redirect(array('create_4','id'=>$model->id));
				else 
					$this->redirect(array('update_4','id'=>$model->id));
			}
		}

		$TagsElement='tags_items_'.$model->Items_ItemsClassliy->admin;
		$model->Items_TagsElement=TagsElement::get_select_tags(constant('TagsElement::'.$TagsElement),$id);
   	
		$tags_model=new Tags;
	
    	$this->render($model->add_time==$model->up_time?'create_3':'update_3',array(
    			'model'=>$model,
    			'tags_model'=>$tags_model->select_tags_element(true),
    	));
    }
    
    /**
     * 创建项目完成页面
     * @param unknown $id
     */
    public function actionCreate_4($id)
    {
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
	    $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
	    $model=$this->loadModel($id,array(
	    		'with'=>array(
	    				'Items_ItemsClassliy'
	    		),
	    		'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
	    		'params'=>array(':audit'=>Items::audit_pending,':agent_id'=>Yii::app()->agent->id),
	    ));
    	$this->render('create_4',array(
    			'model'=>$model,
    	));
    }
    
    /**
     * 更新完成页面
     * @param unknown $id
     */
    public function actionUpdate_4($id)
    {
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
    	$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
    	
    	$model=$this->loadModel($id,array(
    			'with'=>array(
    					'Items_ItemsClassliy'
    			),
    			'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
    			'params'=>array(':audit'=>Items::audit_pending,':agent_id'=>Yii::app()->agent->id),
    	));
    	$this->render('update_4',array(
    			'model'=>$model,
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
    		$model= $this->loadModel($id,array(
				'with'=>array(
					'Items_ItemsClassliy'
				),
				'condition'=>'`t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
				'params'=>array(':audit'=>Items::audit_draft,':agent_id'=>Yii::app()->agent->id),
    		));
    		$TagsElement='tags_items_'.$model->Items_ItemsClassliy->admin;

    		$tags_id=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
    		if($type=='yes'){

                $model->Items_TagsElement=TagsElement::get_select_tags(constant('TagsElement::'.$TagsElement),$id);
                if(count($model->Items_TagsElement)>=Yii::app()->params['tags']['items']['select'])
                {
                    echo Yii::app()->params['tags']['items']['error'];
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
    				$this->log('项目('.$model->Items_ItemsClassliy->name.')添加一个标签', ManageLog::agent,ManageLog::create);
    			else
    				$this->log('项目('.$model->Items_ItemsClassliy->name.')去除一个标签', ManageLog::agent,ManageLog::clear);
    			echo 1;
    		}else
    			echo '操作过于频繁，请刷新页面从新选择！';
    	}else
    		echo '没有选中标签，请重新选择标签！';
    }

    /**
     * 项目删除
     * @param integer $id
     */
    public function actionDelete($id)
    {
        $this->_class_model='Items';

        $result = $this->loadModel($id,
            '`agent_id`=:agent_id AND `audit`!=:audit AND `status`=:status',
            array(
                ':agent_id'=>Yii::app()->agent->id,
                ':audit'=>Items::audit_pending,
                ':status'=>Items::status_offline,
            )
        )->updateByPk($id,array('status'=>Items::status_del));

        if($result)
            $this->log('代理商删除项目',ManageLog::agent,ManageLog::delete);

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }

    /**
     * 项目上线
     * @param integer $id
     */
    public function actionStart($id)
    {
        $this->_class_model='Items';

        $result = $this->loadModel($id,
            '`agent_id`=:agent_id AND `audit`=:audit AND `status`=:status',
            array(
                ':agent_id'=>Yii::app()->agent->id,
                ':audit'=>Items::audit_pass,
                ':status'=>Items::status_offline,
            )
        )->updateByPk($id,array('status'=>Items::status_online));

        if($result)
            $this->log('代理商上线项目',ManageLog::agent,ManageLog::update);
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }

    /**
     * 项目下线
     * @param integer $id
     */
    public function actionDisable($id)
    {
        $this->_class_model='Items';
        $result = $this->loadModel($id,
            '`agent_id`=:agent_id AND `audit`=:audit AND `status`=:status',
            array(
                ':agent_id'=>Yii::app()->agent->id,
                ':audit'=>Items::audit_pass,
                ':status'=>Items::status_online,
            )
        )->updateByPk($id,array('status'=>Items::status_offline));

        if($result)
            $this->log('代理商下线项目',ManageLog::agent,ManageLog::update);
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }
}
