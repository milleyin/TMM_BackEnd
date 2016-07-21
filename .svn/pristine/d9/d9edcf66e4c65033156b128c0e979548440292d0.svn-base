<?php
/**
 * 
 * @author Changhai Zhan
 *    创建时间：2016-04-06 16:06:32 */
class PlayController extends OperatorMainController
{
    /**
     * 添加 csrf白名单
     * @var unknown
     */
    public $enableCsrfValidation = array('uploads' => false);
    /**
     * 默认操作数据模型
     * @var string
     */
    public $_class_model = 'Play';

    /**
     * 查看详情
     * @param integer $id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, array(
                'with'=>array(
                        'Play_Items'=>array(
                                'condition'=>'Play_Items.status!=:status',
                                'params'=>array(':status'=>Items::status_del),
                                'with'=>array(
                                        'Items_StoreContent'=>array('with'=>array('Content_Store')),
                                        'Items_area_id_p_Area_id'=>array('select'=>'name'),
                                        'Items_area_id_m_Area_id'=>array('select'=>'name'),
                                        'Items_area_id_c_Area_id'=>array('select'=>'name'),
                                        'Items_agent',
                                )
                        ),
                        'Play_ItemsClassliy'=>array('select'=>'name'),
                        'Play_Fare',
                        'Play_ItemsImg',
                )
        ));
        //加载标签
        $model->Play_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_play, $id);
        
        $this->render('view', array('model'=>$model));
    }

    /**
     * 创建
     */
    public function actionCreate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/operator/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/operator/ext/uploadify/jquery.uploadify.min.js');
        //实例化类
        $model = new Play;
        $model->Play_Items = new Items;
        $model->Play_ItemsImg = new ItemsImg;
        //设置创建项目 场景
        $model->Play_Items->scenario = 'create';
        //添加 或者 减去 价格类型
        if ((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items'], $_POST['Fare']) && is_array($_POST['Fare']))
        {
            //价格不免费
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = $this->set_number('Fare', Yii::app()->params['items_fare_number']);
                //创建多个model
                $model->Play_Fare = $this->new_modes('Fare', 'create_play', $number);
                //赋值
                $this->set_attributes($model->Play_Fare);
            }
            else
            {
                //价格免费
                $default = array('name' =>'免费门票', 'price' =>'0.00');
                //创建模型
                $model->Play_Fare = $this->new_modes('Fare', 'create_play', count(Fare::$__info));
                $array = array();
                //赋值 免费的
                foreach ($model->Play_Fare as $key_free=>$info)
                {
                    $default['info'] = Fare::$__info[$key_free == 0 ? 1 : 0];
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Play_Fare = $array;
            }
            //赋值
            $model->Play_Items->attributes = $_POST['Items'];
        }
        else if (isset($_POST['Items'], $_POST['Fare']) && is_array($_POST['Fare']))
        {
            //提交 设置价格  不是免费的
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = count($_POST['Fare']);
                //不能超出限制
                if ($number > Yii::app()->params['items_fare_number'])
                    $number = Yii::app()->params['items_fare_number'];    
                $model->Play_Fare = $this->new_modes('Fare', 'create_play', $number);
            }
            else
            {
                $default = array('name' =>'免费门票', 'info'=>'', 'price' =>'0.00');
                $model->Play_Fare = $this->new_modes('Fare', 'create_play', count(Fare::$__info));
                $array = array();
                foreach ($model->Play_Fare as $key_free=>$info)
                {
                    $default['info'] = Fare::$__info[$key_free == 0 ? 1 : 0];
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Play_Fare = $array;
            }
            //赋值
            $model->Play_Items->attributes = $_POST['Items'];
        }
        else
            //默认一个
            $model->Play_Fare = $this->new_modes('Fare', 'create_play');

        //供应商主账号
        $this->_class_model = 'StoreContent';
        $model->Play_Items->Items_StoreContent = $this->loadModel($id, array(
                    'with'=>array(
                            'Content_Store'=>array(
                                    'condition'=>'Content_Store.agent_id=:agent_id AND Content_Store.status=:status',
                                    'params'=>array(':agent_id'=>Yii::app()->operator->id, ':status'=>StoreUser::status_suc),
                            ),
                            'Content_Stoer_Son',
                    ),
        ));
        //设置供应商
        $model->Play_Items->store_id = $model->Play_Items->Items_StoreContent->id;
        //aja 验证
        $this->_Ajax_Verify_Same(array_merge($model->Play_Fare, array($model->Play_Items)), 'play-form');
        //提交表单 排除添加价格类型
        if    (!isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items'], $_POST['Fare']) &&
                is_array($_POST['Fare']) && count($_POST['Fare']) == count($model->Play_Fare)
            )
        {
            //赋值
            $model->Play_Items->attributes = $_POST['Items'];
            //玩
            $model->Play_Items->c_id = Items::items_play;
            //归属运营商
            $model->Play_Items->agent_id = Yii::app()->operator->id;
            //归属供应商
            $model->Play_Items->store_id = $model->Play_Items->Items_StoreContent->id;
            //项目字段验证
            $Items_validate = $model->Play_Items->validate();
            //项目价格赋值 并验证 
            $Fare_validate = $this->models_validate($model->Play_Fare);
            //内容验证
            $content_validate = false;
            if ($model->Play_Items->content == '')
            {
                $model->Play_Items->addError('content', '详细内容 不可空白');
                $content_validate = false;
            }
            else
            {
                //处理图片链接
                $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                $content_validate = true;
            }
            //验证图片
            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                $img_validate = true;
                $this->_upload = Yii::app()->params['uploads_items_tmp_play'];
                $filename = $this->_upload . date('Ymd') . '/' . current($_POST['ItemsImg']['tmp']);
                if (! $this->file_exists_uploads($filename))
                {
                    $model->Play_ItemsImg->addError('tmp', '概况图 不可空白');
                    $img_validate = false;
                }
                else if (count($_POST['ItemsImg']['tmp']) > Yii::app()->params['items_image_number'])
                {
                    $model->Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $img_validate = false;
                }
            }
            else
            {
                $model->Play_ItemsImg->addError('tmp', '概况图 不可空白');
                $img_validate = false;
            }
            //提前验证都通过
            if ($Fare_validate && $Items_validate && $img_validate && $content_validate)
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    //创建的项目是下线状态
                    $model->Play_Items->status = Items::status_offline;
                    //创建的默认未提交
                    $model->Play_Items->audit = Items::audit_draft;
                    //处理图片链接
                    $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                    //项目图片地址
                    $model->Play_Items->map = $this->getFilePath(Yii::app()->params['uploads_items_map']) . '.png';
                    //保存项目主要表
                    if ($model->Play_Items->save(false))
                    {
                        //保存图片
                        Items::saveAmapImage($model->Play_Items->map, $model->Play_Items->lng, $model->Play_Items->lat);                        
                        $model->id = $model->Play_Items->id;
                        $model->c_id = $model->Play_Items->c_id;
                        if ($model->save(false))
                        {
                            foreach ($model->Play_Fare as $model_fare)
                            {
                                $model_fare->store_id =$model->Play_Items->store_id;
                                $model_fare->agent_id = $model->Play_Items->agent_id;
                                $model_fare->item_id = $model->Play_Items->id;
                                $model_fare->c_id = $model->Play_Items->c_id;
                                if ( !$model_fare->save(false))
                                    throw new Exception("添加项目玩价格记录错误");
                            }
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //项目 住 图片缓存地址
                                $this->_upload = Yii::app()->params['uploads_items_tmp_play'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                //图片是否存在
                                if ($this->file_exists_uploads($filename))
                                {
                                    $this->_upload = Yii::app()->params['uploads_items_play'];
                                    //保存图片
                                    if ( !$this->items_img_save($model->Play_Items, $filename))
                                        throw new Exception("添加项目(玩)图片记录错误");
                                }
                            }
                            $return = $this->log('添加项目(玩)', ManageLog::operator, ManageLog::create);
                        }
                        else 
                            throw new Exception("添加项目玩记录错误");
                    }
                    else
                        throw new Exception("添加项目(玩)主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::operator, ErrorLog::create, ErrorLog::rollback, __METHOD__);
                }
                if(isset($return) && $return)
                    $this->back();
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }
    
    /**
     * 更新
     * @param integer $id
     */
    public function actionUpdate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/operator/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/operator/ext/uploadify/jquery.uploadify.min.js');
        //加载项目
        $model = $this->loadModel($id, array('with'=>array(
                        'Play_ItemsImg',
                        'Play_Fare',
                        'Play_Items'=>array('with'=>array(
                                    'Items_StoreContent'=>array('with'=>array(
                                                'Content_Store',
                                                'Content_Stoer_Son',
                                    ))
                        ))
                ),
                'condition'=>'Play_Items.status=:status AND t.c_id=:c_id AND Play_Items.audit !=:audit AND Play_Items.agent_id=:agent_id',
                'params'=>array(':status'=>Items::status_offline, ':c_id'=>Items::items_play, ':audit'=>Items::audit_pending, ':agent_id'=>Yii::app()->operator->id),
        ));
        //获取价格 对象中字段 ID
        $fare_ids = $this->listData($model->Play_Fare, 'id', 'id');
        //获取概况图 对象中字段 ID
        $items_img_ids = $this->listData($model->Play_ItemsImg, 'id', 'id');
        //设置 价格对象 场景
        $this->set_scenarios($model->Play_Fare, 'update_play');
        //设置 项目 吃 的场景
        $model->Play_Items->scenario = 'update';
        //验证 是否是添加 减去价格
        if ((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items'], $_POST['Fare']) && is_array($_POST['Fare']))
        {
            //不免费
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                //设置有多少个fare
                $number = $this->set_number('Fare', Yii::app()->params['items_fare_number']);
                $array = array();
                $default = array('name' =>'', 'info' =>'', 'price' =>'');
                $count = count($model->Play_Fare);
                //设置属性 保存数据
                $array = $this->set_attributes($model->Play_Fare, $default, $number);
                //原来的小 需要在添加对象
                if ($number > $count )
                {
                    $array = array_merge($array, $this->new_modes('Fare', 'update_play', $number-$count));
                    //设置属性 保存数据
                    $array = $this->set_attributes($array, $default, $number);
                }
                //赋值对象
                $model->Play_Fare = $array;
            }
            else
            {
                // 价格免费
                $default = array('name' =>'免费门票', 'price' =>'0.00');
                $model->Play_Fare = $this->update_models($model->Play_Fare, count(Fare::$__info), 'update_play');
                $array = array();
                foreach ($model->Play_Fare as $key_free=>$info)
                {
                    $default['info'] = Fare::$__info[$key_free == 0 ? 1 : 0];
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Play_Fare = $array;
            }
            //保存项目的值
            $model->Play_Items->attributes = $_POST['Items'];
            //图片
            if (isset($_POST['ItemsImg']) && is_array($_POST['ItemsImg']))
            {
                $ids_img = $this->array_listData($_POST['ItemsImg'], 'id');
                  //过滤 id
                $models_img = ItemsImg::filter_id($model->id, $ids_img, false);
                   //赋值对象与数据
                $model->Play_ItemsImg = $this->models_attributes($model->Play_ItemsImg, $models_img, array('id', 'img'));
            }
        }
        else if (isset($_POST['Fare']) && is_array($_POST['Fare']))
        {
            //提交价格 不是免费的
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = count($_POST['Fare']);
                if ($number > Yii::app()->params['items_fare_number'])
                    $number = Yii::app()->params['items_fare_number'];
                //更新models
                $model->Play_Fare = $this->update_models($model->Play_Fare, $number, 'update_play');
            }
            else
            {
                //免费的
                $default = array('name' =>'免费套餐', 'price' =>'0.00');
                //更新对象组
                $model->Play_Fare = $this->update_models($model->Play_Fare, count(Fare::$__info), 'update_eat');
                $array = array();
                foreach ($model->Play_Fare as $key_free=>$info)
                {
                    $default['info'] = Fare::$__info[$key_free == 0 ? 1 : 0];
                    $info->attributes = $default;
                    $array[] = $info;
                }
                //赋值属性
                $model->Play_Fare = $array;
            }
            //项目属性赋值
            $model->Play_Items->attributes = $_POST['Items'];
        }
        //设置价格场景
        $this->set_scenarios($model->Play_Fare, 'update_play');
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Play_Fare, array($model->Play_Items)), 'play-form');
        //提交表单
        if ( !isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items'], $_POST['Fare']) &&
                is_array($_POST['Fare']) && count($_POST['Fare']) == count($model->Play_Fare)
            )
        {
            //原来的图片
            $old_map = $model->Play_Items->map;
            $old_lng = $model->Play_Items->lng;
            $old_lat = $model->Play_Items->lat;
            //项目属性赋值
            $model->Play_Items->attributes = $_POST['Items'];
            //项目字段验证
            $Items_validate = $model->Play_Items->validate();
            //项目价格赋值 并验证
            $Fare_validate = $this->models_validate($model->Play_Fare);
            //获取图片id
            $img_ids = $this->array_listData($_POST['ItemsImg'], 'id');
            //过滤 id 剩下的id
            if (!empty($img_ids))
                    $img_ids = ItemsImg::filter_id($model->id,$img_ids);
            //获取所有的返回属性组成的数组
            $img_path_array = ItemsImg::filter_id($model->id, '', false, array('id'=>'img'));
            //内容验证
            $content_validate = false;
            if ($model->Play_Items->content == '')
            {
                $model->Play_Items->addError('content', '详细内容 不可空白');
                $content_validate = false;
            }
            else
            {
                //处理图片链接
                $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                $content_validate = true;
            }
            //是否存在图片对象
            if (!empty($model->Play_ItemsImg) && isset($model->Play_ItemsImg[0]))
                $Play_ItemsImg = $model->Play_ItemsImg[0];
            else
            {
                $model->Play_ItemsImg = array( new ItemsImg );
                $Play_ItemsImg = $model->Play_ItemsImg[0];
            }
            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                if ((count($img_ids)+count($_POST['ItemsImg']['tmp']))>Yii::app()->params['items_image_number'])
                {
                    $Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $items_img_validate = false;
                }
                else if ((count($img_ids)+count($_POST['ItemsImg']['tmp'])) == 0)
                {
                    $Play_ItemsImg->addError('tmp', '概况图 不可空白');
                    $items_img_validate = false;
                }
                else
                    $items_img_validate = true;
            }
            else if (count($img_ids)>Yii::app()->params['items_image_number'])
            {
                $Play_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                $items_img_validate = false;
            }
            else if (count($img_ids)==0)
            {
                $Play_ItemsImg->addError('tmp', '概况图 不可空白');
                $items_img_validate = false;
            }
            else
                $items_img_validate = true;
            //提前验证都通过
            if ($Fare_validate && $Items_validate && $items_img_validate && $content_validate)
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    //修改未提交
                    $model->Play_Items->audit = Items::audit_draft;
                    //处理图片链接
                    $model->Play_Items->content = $this->admin_img_replace($model->Play_Items->content);
                    //项目图片地址
                    if((string)$old_lng != (string)$model->Play_Items->lng || (string)$old_lat != (string)$model->Play_Items->lat)
                        $model->Play_Items->map = $this->getFilePath(Yii::app()->params['uploads_items_map']) . '.png';
                    else
                        $model->Play_Items->map = $old_map;
                    //保存项目主要表
                    if ($model->Play_Items->save(false))
                    {
                        //保存图片
                        if ($model->Play_Items->map != $old_map)
                        {
                            Items::saveAmapImage($model->Play_Items->map, $model->Play_Items->lng, $model->Play_Items->lat);
                            if ($this->file_exists_uploads($old_map))
                                unlink($this->get_file_uploads($old_map));
                        }
                        foreach ($model->Play_Fare as $model_fare)
                        {
                            $model_fare->store_id = $model->Play_Items->store_id;
                            $model_fare->agent_id = $model->Play_Items->agent_id;
                            $model_fare->item_id = $model->Play_Items->id;
                            $model_fare->c_id = $model->Play_Items->c_id;
                            if ( !$model_fare->save(false))
                                throw new Exception("添加项目(玩)价格记录错误");
                            if (isset($fare_ids[$model_fare->id]))
                                unset($fare_ids[$model_fare->id]);
                        }
                        // 删除不用的价格记录
                        if ( !empty($fare_ids))
                        {
                            if ( !Fare::model()->deleteAll(array('condition'=>'id in (' . implode(',', $fare_ids)  . ')' )))
                                throw new Exception("删除项目(玩)不用的价格记录错误");
                        }
                        if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
                        {
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //保存上传的项目图片多张
                                $this->_upload = Yii::app()->params['uploads_items_tmp_play'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                if ($this->file_exists_uploads($filename))
                                {
                                    $this->_upload = Yii::app()->params['uploads_items_play'];
                                    //保存图片
                                    if ( !$this->items_img_save($model->Play_Items, $filename))
                                        throw new Exception("添加项目(玩)图片记录错误");
                                }
                            }
                        }
                        // 删除图片记录
                        foreach ($items_img_ids as $items_img_id)
                        {
                            if ( !in_array($items_img_id, $img_ids) && ItemsImg::model()->deleteByPk($items_img_id))
                                $this->upload_delete($img_path_array[$items_img_id]);
                        }
                        $return = $this->log('修改项目(玩)', ManageLog::operator, ManageLog::update);
                    }
                    else
                        throw new Exception("修改项目玩主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(), ErrorLog::operator, ErrorLog::update, ErrorLog::rollback, __METHOD__);
                }
                if(isset($return) && $return)
                    $this->back();
            }
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 上传图成功 删除
     */
    public function actionUploads()
    {
        $this->_upload = Yii::app()->params['uploads_items_tmp_play'];
        if (isset($_POST['file_name']))
        {
            $filename = $this->_upload . date('Ymd') . '/' . $_POST['file_name'];
            if ($this->file_exists_uploads($filename))
                echo unlink($this->get_file_uploads($filename));
            else
                echo 0;
            Yii::app()->end();
        }
        $model = new ItemsImg;
        $model->scenario = 'uploads';
        $uploads = array('tmp');
        if ($this->upload_images($model, $uploads, true))
            echo json_encode(array('img_name'=>basename($model->tmp), 'litimg'=>$this->get_file_uploads($this->litimg_path($model->tmp, Yii::app()->params['litimg_pc']))));
        else
            echo json_encode(array('img_name'=>'none'));
        Yii::app()->end();
    }
}
