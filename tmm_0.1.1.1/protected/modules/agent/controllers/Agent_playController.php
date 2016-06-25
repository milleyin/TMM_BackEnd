<?php
/**
 *
 * @author Changhai Zhan
 *	创建时间：2015-09-07 17:27:05 */
class Agent_playController extends AgentController
{
    /**
     * 默认操作数据模型
     * @var string
     */
    public $_class_model='Play';
    /**
     * 创建第二部
     * @param unknown $id
     */
    public function actionCreate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
        $this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');

        $model= new Play;
        $model->Play_Items=new Items;
        $model->Play_ItemsImg= new ItemsImg;

        $model->Play_Items->scenario='agent_create_play';
        if(isset($_POST['Fare']) && is_array($_POST['Fare']))
        {
            $number=count($_POST['Fare']);
            if($number > Yii::app()->params['items_fare_number'])
                $number=Yii::app()->params['items_fare_number'];
            $model->Play_Fare=$this->new_modes('Fare', 'create_play',$number);
        }else
            $model->Play_Fare=$this->new_modes('Fare','create_play');
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Play_Fare,array($model->Play_Items)),'play-form');

        $this->_class_model='StoreUser';
        $model->Play_Items->Items_Store_Manager=$this->loadModel($id,array(
            'condition'=>'`status`=1 AND `agent_id`=:agent_id',
            'params'=>array(':agent_id'=>Yii::app()->agent->id),
        ));
        //获取项目类型
        $this->_class_model='Play';
        $model->Play_Items->c_id=ItemsClassliy::getClass()->id;//玩

        if(isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare'])==count($model->Play_Fare))
        {
            $model->Play_Items->attributes=$_POST['Items'];
            //赋值
            $model->Play_Items->agent_id=Yii::app()->agent->id;
            if($model->Play_Items->Items_Store_Manager->p_id==0)
                $model->Play_Items->store_id=$model->Play_Items->Items_Store_Manager->id;
            else
                $model->Play_Items->store_id=$model->Play_Items->Items_Store_Manager->p_id;
            $model->Play_Items->manager_id=$model->Play_Items->Items_Store_Manager->id;
            //上传图片
            $this->_upload=Yii::app()->params['uploads_items_map'];
            $uploads=	array('map');
            $files=$this->upload($model->Play_Items,$uploads);
            //图片验证
            $Items_img_validate=$this->upload_error($model->Play_Items, $files, $uploads);
            if($Items_img_validate)
                $Items_validate=$model->Play_Items->validate();	//项目字段验证
            else
                $Items_validate=false;
            //项目价格验证
            $Fare_validate=$this->models_validate($model->Play_Fare);

            if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                //验证图片
                $this->_upload=Yii::app()->params['uploads_items_tmp_play'];
                $filename=$this->_upload.date('Ymd').'/'.current($_POST['ItemsImg']['tmp']);
                if(! file_exists($filename))
                {
					$model->Play_ItemsImg('tmp', '概况图 不可空白');
                    $img_validate=false;
                }elseif(count($_POST['ItemsImg']['tmp'])>Yii::app()->params['items_image_number']){
					$model->Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $img_validate=false;
                }else
                    $img_validate=true;
            }else{
				$model->Play_ItemsImg->addError('tmp', '概况图 不可空白');
                $img_validate=false;
            }

            //提前验证都通过
            if($Fare_validate && $Items_validate && $Items_img_validate && $img_validate)
            {
                $transaction=$model->dbConnection->beginTransaction();
                try
                {
                    $model->Play_Items->status=0;//创建的项目是下线状态
                    $model->Play_Items->audit=items::audit_draft;//创建的默认未提交
                    //处理图片链接
                    $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                    if($model->Play_Items->save(false))//保存项目主要表
                    {
                        $model->id=$model->Play_Items->id;
                        $model->c_id=$model->Play_Items->c_id;
                        if(!$model->save(false))						//保存项目附表
                            throw new Exception("添加项目(玩)记录错误");
                        else{
                            //价格
                            foreach ($model->Play_Fare as $model_fare)
                            {
                                $model_fare->store_id=$model->Play_Items->store_id;
                                $model_fare->agent_id=Yii::app()->agent->id;
                                $model_fare->item_id=$model->Play_Items->id;
                                $model_fare->c_id=$model->Play_Items->c_id;
                                if(! $model_fare->save(false))
                                    throw new Exception("添加项目(玩)价格记录错误");
                            }
                            //图片
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //保存上传的项目图片多张
                                $this->_upload=Yii::app()->params['uploads_items_tmp_play'];
                                $filename=$this->_upload.date('Ymd').'/'.$name;
                                if(file_exists($filename))
                                {
                                    $this->_upload=Yii::app()->params['uploads_items_play'];
                                    if(!$this->items_img_save($model->Play_Items,$filename))//保存图片
                                        throw new Exception("添加项目(玩)图片记录错误");
                                }
                            }
                            $this->upload_save($model->Play_Items,$files);
                            $return =$this->log('添加项目(玩)',ManageLog::agent,ManageLog::create);
                        }
                    }else
                        throw new Exception("添加项目(玩)主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
                }
                if(isset($return))
                    $this->redirect(array('/agent/agent_items/create_3','id'=>$model->id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * 更新项目玩
     */
    public function actionUpdate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
        $this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');

        $model=$this->loadModel($id,array(
            'with'=>array(
                'Play_Items'=>array(
                    'with'=>array(
                        'Items_StoreContent'=>array('with'=>'Content_Store'),
                    )
                ),
                'Play_ItemsImg',
                'Play_Fare',
            ),
            'condition'=>'`Content_Store`.status=1 AND `Play_Items`.`status`=0 AND `t`.`c_id`=:c_id AND `Play_Items`.`audit` !=:audit AND `Play_Items`.`agent_id`=:agent_id',
            'params'=>array(':c_id'=>ItemsClassliy::getClass()->id,':audit'=>Items::audit_pending,':agent_id'=>Yii::app()->agent->id),
        ));

        $fare_count=count($model->Play_Fare);
        $fare_ids=$this->listData($model->Play_Fare, 'id');
        $items_img_ids=$this->listData($model->Play_ItemsImg, 'id');

        if(isset($_POST['Fare']) && is_array($_POST['Fare']))
        {
            $number=count($_POST['Fare']);
            if($number > Yii::app()->params['items_fare_number'])
                $number=Yii::app()->params['items_fare_number'];
            //更新models
            $model->Play_Fare=$this->update_models($model->Play_Fare, $number, 'update_play');
        }
        $this->set_scenarios($model->Play_Fare, 'update_play');
        $model->Play_Items->scenario='agent_update_play';

        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Play_Fare,array($model->Play_Items)),'play-form');
        //获取项目类型
        $this->_class_model='Play';
        $model->Play_Items->c_id=ItemsClassliy::getClass()->id;//吃

        if(isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare'])  && count($_POST['Fare'])==count($model->Play_Fare))
        {
            //上传图片
            $this->_upload=Yii::app()->params['uploads_items_map'];
            $uploads=array('map');
            $data=$this->upload_save_data($model->Play_Items, $uploads);//保存原来的

            $files=$this->upload($model->Play_Items,$uploads);
            
            $model->Play_Items->attributes=$_POST['Items'];
            //项目字段验证
            $Items_validate=$model->Play_Items->validate();
            $old_path=$this->upload_update_data($model->Play_Items, $data, $files);//还原原来的值
            //项目价格验证
            $Fare_validate=$this->models_validate($model->Play_Fare);

            //获取图片id
            $img_ids=$this->array_listData($_POST['ItemsImg'], 'id');
            if(!empty($img_ids))
                $img_ids=ItemsImg::filter_id($model->id,$img_ids);//过滤 id 剩下的id
            $img_path_array=ItemsImg::filter_id($model->id,'',false,array('id'=>'img'));//获取所有的返回属性组成的数组
			if(!empty($model->Play_ItemsImg) && isset($model->Play_ItemsImg[0]))
				$Play_ItemsImg=$model->Play_ItemsImg[0];
			else{
				$model->Play_ItemsImg=array(new ItemsImg);
				$Play_ItemsImg=$model->Play_ItemsImg[0];
			}
            if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                if((count($img_ids)+count($_POST['ItemsImg']['tmp']))>Yii::app()->params['items_image_number'])
                {
					$Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $items_img_validate=false;
                }elseif((count($img_ids)+count($_POST['ItemsImg']['tmp']))==0){
					$Play_ItemsImg->addError('tmp', '概况图 不可空白');
                    $items_img_validate=false;
				}else 
					$items_img_validate=true;
            }elseif(count($img_ids)>Yii::app()->params['items_image_number']){
				$Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                $items_img_validate=false;
            }elseif(count($img_ids)==0){
				$Play_ItemsImg->addError('tmp', '概况图 不可空白');	
                $items_img_validate=false;
            }else
                $items_img_validate=true;

            //提前验证都通过
            if($Fare_validate && $Items_validate && $items_img_validate)
            {
                $transaction=$model->dbConnection->beginTransaction();
                try
                {
                    $model->Play_Items->audit=Items::audit_draft;//修改未提交
                    //处理图片链接
                    $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                    if($model->Play_Items->save(false))//保存项目主要表
                    {
                        foreach ($model->Play_Fare as $model_fare)
                        {
                            $model_fare->store_id=$model->Play_Items->store_id;
                            $model_fare->agent_id=Yii::app()->agent->id;
                            $model_fare->item_id=$model->id;
                            $model_fare->c_id=$model->Play_Items->c_id;
                            if(! $model_fare->save(false))
                                throw new Exception("添加项目(玩)价格记录错误");
                            $fare_count--;
                        }
                        if($fare_count >0)
                        {
                            rsort($fare_ids);
                            for($j=0;$j<$fare_count;$j++)
                            {
                                Fare::model()->deleteByPk($fare_ids[$j]);
                            }
                        }
                        if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
                        {
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //保存上传的项目图片多张
                                $this->_upload=Yii::app()->params['uploads_items_tmp_play'];
                                $filename=$this->_upload.date('Ymd').'/'.$name;
                                if(file_exists($filename))
                                {
                                	$this->_upload=Yii::app()->params['uploads_items_play'];
                                    if(!$this->items_img_save($model->Play_Items,$filename))//保存图片
                                        throw new Exception("添加项目(玩)图片记录错误");
                                }
                            }
                        }
                        foreach ($items_img_ids as $items_img_id)
                        {
                            if(!in_array($items_img_id, $img_ids))
                            {
                                ItemsImg::model()->deleteByPk($items_img_id);
                                $this->upload_delete($img_path_array[$items_img_id]);
                            }
                        }
                        $this->upload_save($model->Play_Items,$files);
                        $this->upload_delete($old_path);//删除原来的
                        $return =$this->log('修改项目(玩)',ManageLog::agent,ManageLog::update);
                    }else
                        throw new Exception("修改项目(玩)主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
                }
                if(isset($return))
                    $this->redirect(array('/agent/agent_items/create_3','id'=>$model->id));
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * 上传图成功 删除
     */
    public function actionUploads()
    {
        $this->_upload=Yii::app()->params['uploads_items_tmp_play'];
        if(isset($_POST['file_name'])){
            $filename=$this->_upload.date('Ymd').'/'.$_POST['file_name'];
            if(file_exists($filename))
                echo unlink($filename);
            else
                echo 0;
            Yii::app()->end();
        }
        $model=new ItemsImg;
        $model->scenario='uploads';
        $uploads=array('tmp');
        if($this->upload_images($model,$uploads,true))
            echo json_encode(array('img_name'=>basename($model->tmp),'litimg'=>$this->litimg_path($model->tmp, Yii::app()->params['litimg_pc'])));
        else
            echo json_encode(array('img_name'=>'none'));
        Yii::app()->end();
    }

    /**
     * 查看项目（玩）详情
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/style.css');

        $model = $this->loadModel($id,array(
            'with'=>array(
                'Play_Items'=>array(
                    'with'=>array(
                        'Items_agent',
                        'Items_StoreContent'=>array('with'=>array('Content_Store')),
                        'Items_Store_Manager',
                        'Items_area_id_p_Area_id'=>array('select'=>'name'),
                        'Items_area_id_m_Area_id'=>array('select'=>'name'),
                        'Items_area_id_c_Area_id'=>array('select'=>'name'),
                    )),
                'Play_ItemsClassliy',
                'Play_ItemsWifi'=>array('with'=>array('ItemsWifi_Wifi')),
                'Play_Fare',
                'Play_ItemsImg',
            ),
        	// 查看自己 除删除  查看别人 (上线，审核通过)
        	'condition' => '(`Play_Items`.`status`>=0 AND `Play_Items`.`agent_id`=:agent_id) OR (`Play_Items`.`status`=1 AND `Play_Items`.`audit`=:audit AND `Play_Items`.`agent_id`!=:agent_id)',
        	'params'=>array(
        			':agent_id'=>Yii::app()->agent->id,
        			':audit'=>Items::audit_pass,
        	),
        ));
        // 标签
        $model->Play_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_play,$id);

        $this->render('view',array('model'=>$model));
    }


}
