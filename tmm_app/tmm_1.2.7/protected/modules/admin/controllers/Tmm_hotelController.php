<?php
/**
 * 
 * @author Changhai Zhan
 *    创建时间：2015-08-11 16:09:40 */
class Tmm_hotelController extends MainController
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
    public $_class_model='Hotel';
    
    /**
     * 查看详情
     * @param integer $id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, array(
                'with'=>array(
                        'Hotel_Items'=>array(
                                'with'=>array(
                                        'Items_StoreContent'=>array('with'=>array('Content_Store')),
                                        'Items_Store_Manager',
                                        'Items_agent',
                                        'Items_area_id_p_Area_id'=>array('select'=>'name'),
                                        'Items_area_id_m_Area_id'=>array('select'=>'name'),
                                        'Items_area_id_c_Area_id'=>array('select'=>'name'),
                                )
                        ),
                        'Hotel_ItemsClassliy'=>array('select'=>'name'),
                        'Hotel_Fare',
                        'Hotel_ItemsImg',
                        'Hotel_ItemsWifi'=>array('with'=>array('ItemsWifi_Wifi')),
                ),
        ));
        // 加载标签
        $model->Hotel_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_hotel, $id);
        
        $this->render('view', array('model'=>$model));
    }

    /**
     * 创建住
     * @param integer $id 供应商 $id
     * @throws Exception
     */
    public function actionCreate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
        //实例化类
        $model = new Hotel;
        $model->Hotel_Items = new Items;
        $model->Hotel_ItemsImg = new ItemsImg;
        //设置验证场景
        $model->Hotel_Items->scenario = 'create';
        //添加 或者 减去 价格类型
        if ((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items'],$_POST['Fare']) && is_array($_POST['Fare']))
        {
            //价格不免费
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = $this->set_number('Fare', Yii::app()->params['items_fare_number']);
                //创建多个model
                $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel', $number);
                //赋值
                $this->set_attributes($model->Hotel_Fare);
            }
            else
            {
                //价格免费
                $array = array();
                $default = array('name' =>'免费套房', 'info'=>45, 'number'=>2, 'price' =>'0.00');
                //创建模型
                $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel');
                //赋值免费的
                foreach ($model->Hotel_Fare as $info)
                {
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Hotel_Fare = $array;
            }
            //赋值
            $model->Hotel_Items->attributes = $_POST['Items'];
        }
        else if (isset($_POST['Items'], $_POST['Fare']) && is_array($_POST['Fare']))
        {
            //提交 设置价格  不是免费的
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = count($_POST['Fare']);
                if ($number > Yii::app()->params['items_fare_number'])
                    $number = Yii::app()->params['items_fare_number'];    
                $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel', $number);
            }
            else
            {
                $array = array();
                $default = array('name' =>'免费套房', 'info'=>45, 'number'=>2, 'price' =>'0.00');
                $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel');
                foreach ($model->Hotel_Fare as $info)
                {
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Hotel_Fare = $array;
            }
            //赋值
            $model->Hotel_Items->attributes = $_POST['Items'];
        }
        else
            //默认一个
            $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel');
        
        //供应商主账号
        $this->_class_model = 'StoreContent';
        $model->Hotel_Items->Items_StoreContent = $this->loadModel($id, array(
                'with'=>array(
                        'Content_Store'=>array(
                                'with'=>array(
                                        'Store_Agent' =>array(
                                                'condition'=>'Store_Agent.status=:status_agent',
                                                'params'=>array(':status_agent'=>Agent::status_suc),
                                        ),
                                ),
                                'condition'=>'Content_Store.status=:status_store',
                                'params'=>array(':status_store'=>StoreUser::status_suc),
                        ),
                        'Content_Stoer_Son'
                ),
        ));
        //设置供应商
        $model->Hotel_Items->store_id = $model->Hotel_Items->Items_StoreContent->id;
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Hotel_Fare,array($model->Hotel_Items)), 'hotel-form');
        //提交表单 排除添加价格类型
        if (!isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items'], $_POST['Fare']) &&
                is_array($_POST['Fare']) && count($_POST['Fare'])==count($model->Hotel_Fare)
            )
        {
            //赋值
            $model->Hotel_Items->attributes = $_POST['Items'];
            // 住
            $model->Hotel_Items->c_id = Items::items_hotel;
            //归属运营商
            $model->Hotel_Items->agent_id = $model->Hotel_Items->Items_StoreContent->Content_Store->agent_id;
            //归属供应商
            $model->Hotel_Items->store_id=$model->Hotel_Items->Items_StoreContent->id;
            //项目字段验证
            $Items_validate = $model->Hotel_Items->validate();
            //项目价格赋值 并验证
            $Fare_validate = $this->models_validate($model->Hotel_Fare);
            //内容验证
            $content_validate = false;
            if ($model->Hotel_Items->content == '')
            {
                $model->Hotel_Items->addError('content', '详细内容 不可空白');
                $content_validate = false;
            }
            else
            {
                //处理图片链接
                $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                $content_validate = true;
            }
            //验证图片
            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                $img_validate = true;
                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                $filename = $this->_upload . date('Ymd') . '/' . current($_POST['ItemsImg']['tmp']);
                if (! $this->file_exists_uploads($filename))
                {
                    $model->Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                    $img_validate = false;
                }
                else if (count($_POST['ItemsImg']['tmp']) > Yii::app()->params['items_image_number'])
                {
                    $model->Hotel_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $img_validate = false;
                }
            }
            else
            {
                $model->Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                $img_validate = false;
            }
            //提前验证都通过
            if($Fare_validate && $Items_validate && $img_validate && $content_validate)
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    //创建项目 默认下线状态
                    $model->Hotel_Items->status = Items::status_offline;
                    //创建项目 默认未提交
                    $model->Hotel_Items->audit = Items::audit_draft;
                    //处理图片链接
                    $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                    //项目图片地址
                    $model->Hotel_Items->map = $this->getFilePath(Yii::app()->params['uploads_items_map']) . '.png';
                    //保存项目主要表
                    if ($model->Hotel_Items->save(false))
                    {
                        //保存图片
                        Items::saveAmapImage($model->Hotel_Items->map, $model->Hotel_Items->lng, $model->Hotel_Items->lat);            
                        $model->id = $model->Hotel_Items->id;
                        $model->c_id = $model->Hotel_Items->c_id;
                        //保存项目 吃 附表
                        if ($model->save(false))
                        {
                            foreach ($model->Hotel_Fare as $model_fare)
                            {
                                $model_fare->store_id = $model->Hotel_Items->store_id;
                                $model_fare->agent_id = $model->Hotel_Items->agent_id;
                                $model_fare->item_id = $model->Hotel_Items->id;
                                $model_fare->c_id = $model->Hotel_Items->c_id;
                                if (! $model_fare->save(false))
                                    throw new Exception("添加项目(住)价格记录错误");
                            }                    
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //保存上传的项目图片多张
                                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                if ($this->file_exists_uploads($filename))
                                {
                                    $this->_upload = Yii::app()->params['uploads_items_hotel'];
                                    //保存图片
                                    if (! $this->items_img_save($model->Hotel_Items,$filename))
                                        throw new Exception("添加项目(住)图片记录错误");
                                }
                            }            
                            $return = $this->log('添加项目(住)', ManageLog::admin, ManageLog::create);
                        }
                        else
                            throw new Exception("添加项目(住)记录错误");
                    }
                    else 
                        throw new Exception("添加项目(住)主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create, ErrorLog::rollback, __METHOD__);
                }
                if(isset($return) && $return)
                    $this->back();
            }
        }
        
        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * 上传图成功 删除
     */
    public function actionUploads()
    {
        $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
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
        //清空图片缓存
        $this->clear_tmp(Yii::app()->params['uploads_items_tmp_hotel']);
        if ($this->upload_images($model, $uploads, true))
            echo json_encode(array('img_name'=>basename($model->tmp), 'litimg'=>$this->rewritePath($this->litimg_path($model->tmp, Yii::app()->params['litimg_pc']))));
        else
            echo json_encode(array('img_name'=>'none'));
        Yii::app()->end();
    }
    
    /**
     * 更新
     * @param integer $id
     */
    public function actionUpdate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
        //加载项目
        $model = $this->loadModel($id, array('with'=>array(
                        'Hotel_ItemsImg',
                        'Hotel_Fare',
                        'Hotel_Items'=>array('with'=>array(
                                'Items_StoreContent'=>array('with'=>array(
                                        'Content_Store'=>array('with'=>'Store_Agent'),
                                        'Content_Stoer_Son',
                                ))
                        )),
                ),
                'condition'=>'Hotel_Items.status=:status AND t.c_id=:c_id AND Hotel_Items.audit!=:audit',
                'params'=>array(':c_id'=>Items::items_hotel, ':status'=>Items::status_offline, ':audit'=>Items::audit_pending),
        ));
        //获取所有价格的ID
        $fare_ids = $this->listData($model->Hotel_Fare, 'id', 'id');
        //获取所有图片的ID
        $items_img_ids = $this->listData($model->Hotel_ItemsImg, 'id', 'id');
        //设置 价格对象 场景
        $this->set_scenarios($model->Hotel_Fare, 'update_hotel');
        //设置 项目 住 的场景
        $model->Hotel_Items->scenario = 'update';
        //验证 是否是添加 减去价格
        if ((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items'], $_POST['Fare']) && is_array($_POST['Fare']))
        {
            //不免费
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                //设置有多少个fare
                $number = $this->set_number('Fare', Yii::app()->params['items_fare_number']);
                $array = array();
                $default = array('name' =>'', 'info' =>'', 'number'=>'','price' =>'');
                $count = count($model->Hotel_Fare);
                //设置属性 保存数据
                $array = $this->set_attributes($model->Hotel_Fare, $default, $number);
                if ($number > $count )
                {
                    $array = array_merge($array, $this->new_modes('Fare', 'update_hotel', $number-$count));
                    //设置属性 保存数据
                    $array = $this->set_attributes($array, $default, $number);
                }
                //赋值对象
                $model->Hotel_Fare = $array;
            }
            else
            {
                // 价格免费
                $array = array();
                $default = array('name' =>'免费套房', 'info'=>45, 'number'=>2, 'price' =>'0.00');
                //更新对象组
                $model->Hotel_Fare = $this->update_models($model->Hotel_Fare, 1, 'update_hotel');
                foreach ($model->Hotel_Fare as $info)
                {
                    $info->attributes = $default;
                    $array[] = $info;
                }
                //赋值对象
                $model->Hotel_Fare = $array;
            }
            //保存项目的值
            $model->Hotel_Items->attributes = $_POST['Items'];
            //图片
            if (isset($_POST['ItemsImg']) && is_array($_POST['ItemsImg']))
            {
                $ids_img = $this->array_listData($_POST['ItemsImg'], 'id');
                //过滤 id
                $models_img = ItemsImg::filter_id($model->id, $ids_img, false);
                //赋值对象与数据
                $model->Hotel_ItemsImg = $this->models_attributes($model->Hotel_ItemsImg, $models_img, array('id', 'img'));
            }
        }
        else if (isset($_POST['Fare'], $_POST['Items']) && is_array($_POST['Fare']))
        {
            // 提交价格 不是免费的
            if (isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
            {
                $number = count($_POST['Fare']);
                if ($number > Yii::app()->params['items_fare_number'])
                    $number = Yii::app()->params['items_fare_number'];
                //更新models
                $model->Hotel_Fare=$this->update_models($model->Hotel_Fare, $number, 'update_hotel');
            }
            else
            {
                $array = array();
                $default = array('name' =>'免费套房', 'info'=>45, 'number'=>2, 'price' =>'0.00');
                //更新对象组
                $model->Hotel_Fare = $this->update_models($model->Hotel_Fare, 1, 'update_hotel');
                foreach ($model->Hotel_Fare as $info)
                {
                    $info->attributes = $default;
                    $array[] = $info;
                }
                $model->Hotel_Fare = $array;
            }
            //项目属性赋值
            $model->Hotel_Items->attributes = $_POST['Items'];
        }
        //设置价格场景
        $this->set_scenarios($model->Hotel_Fare, 'update_hotel');
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Hotel_Fare, array($model->Hotel_Items)), 'hotel-form');
        //提交表单 排除添加 去除价格类型
        if (!isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items'], $_POST['Fare']) &&
                is_array($_POST['Fare']) && count($_POST['Fare']) == count($model->Hotel_Fare)
        )
        {
            //原来的图片
            $old_map = $model->Hotel_Items->map;
            $old_lng = $model->Hotel_Items->lng;
            $old_lat = $model->Hotel_Items->lat;
            //项目属性赋值
            $model->Hotel_Items->attributes = $_POST['Items'];
            //项目字段验证
            $Items_validate = $model->Hotel_Items->validate();
            //项目价格赋值 并验证
            $Fare_validate = $this->models_validate($model->Hotel_Fare);
            //获取图片id
            $img_ids=$this->array_listData($_POST['ItemsImg'], 'id');
            //过滤 id 剩下的id
            if (!empty($img_ids))
                $img_ids = ItemsImg::filter_id($model->id, $img_ids);
            //获取所有的返回属性组成的数组
            $img_path_array = ItemsImg::filter_id($model->id, '', false, array('id'=>'img'));
            //内容验证
            $content_validate = false;
            if ($model->Hotel_Items->content == '')
            {
                $model->Hotel_Items->addError('content', '详细内容 不可空白');
                $content_validate = false;
            }
            else
            {
                //处理图片链接
                $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                $content_validate = true;
            }
            //是否存在图片对象
            if (!empty($model->Hotel_ItemsImg) && isset($model->Hotel_ItemsImg[0]))
                $Hotel_ItemsImg = $model->Hotel_ItemsImg[0];
            else
            {
                $model->Hotel_ItemsImg = array( new ItemsImg );
                $Hotel_ItemsImg = $model->Hotel_ItemsImg[0];
            }
            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
            {
                if ((count($img_ids)+count($_POST['ItemsImg']['tmp']))>Yii::app()->params['items_image_number'])
                {
                    $Hotel_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                    $items_img_validate = false;
                }
                else if ((count($img_ids)+count($_POST['ItemsImg']['tmp'])) == 0)
                {
                    $Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                    $items_img_validate = false;
                }
                else
                    $items_img_validate = true;
            }
            else if (count($img_ids)>Yii::app()->params['items_image_number'])
            {
                $Hotel_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
                $items_img_validate = false;
            }
            else if (count($img_ids)==0)
            {
                $Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
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
                    $model->Hotel_Items->audit = Items::audit_draft;
                    //处理图片链接
                    $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                    //项目图片地址
                    if((string)$old_lng != (string)$model->Hotel_Items->lng || (string)$old_lat != (string)$model->Hotel_Items->lat)
                        $model->Hotel_Items->map = $this->getFilePath(Yii::app()->params['uploads_items_map']) . '.png';
                    else
                        $model->Hotel_Items->map = $old_map;
                    //保存项目主要表
                    if ($model->Hotel_Items->save(false))
                    {
                        //保存图片
                        if($model->Hotel_Items->map != $old_map)
                        {
                            Items::saveAmapImage($model->Hotel_Items->map, $model->Hotel_Items->lng, $model->Hotel_Items->lat);
                            if ($this->file_exists_uploads($old_map))
                                unlink($this->get_file_uploads($old_map));
                        }
                        foreach ($model->Hotel_Fare as $model_fare)
                        {
                            $model_fare->store_id = $model->Hotel_Items->store_id;
                            $model_fare->agent_id = $model->Hotel_Items->agent_id;
                            $model_fare->item_id = $model->Hotel_Items->id;
                            $model_fare->c_id = $model->Hotel_Items->c_id;
                            if (! $model_fare->save(false))
                                throw new Exception("添加项目(住)价格记录错误");
                            if (isset($fare_ids[$model_fare->id]))
                                unset($fare_ids[$model_fare->id]);
                        }
                        // 删除不用的价格记录
                        if ( !empty($fare_ids))
                        {
                            if ( !Fare::model()->deleteAll(array('condition'=>'id in (' . implode(',', $fare_ids)  . ')' )))
                                throw new Exception("删除项目(住)不用的价格记录错误");
                        }
                        if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
                        {
                            foreach ($_POST['ItemsImg']['tmp'] as $name)
                            {
                                //保存上传的项目图片多张
                                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                if ($this->file_exists_uploads($filename))
                                {
                                    $this->_upload = Yii::app()->params['uploads_items_hotel'];
                                    //保存图片
                                    if ( !$this->items_img_save($model->Hotel_Items, $filename))
                                        throw new Exception("添加项目(住)图片记录错误");
                                }
                            }
                        }
                        // 删除图片记录
                        foreach ($items_img_ids as $items_img_id)
                        {
                            if ( ! in_array($items_img_id, $img_ids) && ItemsImg::model()->deleteByPk($items_img_id))
                                $this->upload_delete($img_path_array[$items_img_id]);
                        }
                        $return = $this->log('修改项目(住)', ManageLog::admin, ManageLog::update);
                    }
                    else
                        throw new Exception("修改项目(住)主要记录错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::update, ErrorLog::rollback, __METHOD__);
                }
                if (isset($return) && $return)
                    $this->back();
            }
        }
        
        $this->render('update', array(
                'model'=>$model,
        ));
    }

    /**
     * 选择wifi
     * @param unknown $id
     */
    public function actionWifi($id)
    {
        $model=new Wifi('search');
        $model->unsetAttributes();  // 删除默认属性
        if(isset($_GET['Wifi']))
            $model->attributes=$_GET['Wifi'];        
        //选择的酒店服务 只能是上线的
        $model->status = 1;
        
        $this->render('wifi',array(
                'model'=>$model,
                'select'=>$this->loadHotel($id)
        ));
    }
    
    /**
     * 加载项目住
     * @param unknown $id
     * @return unknown
     */
    public function loadHotel($id)
    {
        return $this->loadModel($id, array(
                'with'=>array('Hotel_Items'),
                'condition'=>'Hotel_Items.status=:status AND Hotel_Items.audit!=:audit',
                'params'=>array(':status'=>Items::status_offline, ':audit'=>Items::audit_pending,),
        ));
    }
    
    /**
     * 更新wifi
     * @param unknown $id
     * @param string $type
     */
    public function actionUpwifi($id)
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
                    $this->log('添加项目(住)酒店服务', ManageLog::admin, ManageLog::create);
                else
                    $this->log('去除项目(住)酒店服务', ManageLog::admin, ManageLog::clear);
                echo 1;
            }
            else
                echo '操作过于频繁，请刷新页面从新选择！';
        }
        else
            echo '没有选中酒店服务，请重新选择！';
    }
    
    /**
     *选择标签的显示
     * @param unknown $id
     */
    public function actionSelect($id)
    {
        $model=new Tags('search');
        $model->unsetAttributes();  // 删除默认属性
        if(isset($_GET['Tags']))
            $model->attributes=$_GET['Tags'];
        
        $this->render('select',array(
                'model'=>$model,
                'select'=>$this->loadModel($id,array(
                        'with'=>array('Hotel_Items'),
                        'condition'=>'Hotel_Items.status=0 AND Hotel_Items.audit !=:audit AND Hotel_Items.c_id=:c_id',
                        'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
                )),
        ));
    }

    /**
     * 标签选中操作
     * @param unknown $id
     * @param string $type
     */
    public function actionTags($id){
        if(isset($_POST['tag_ids']) && $_POST['tag_ids'] && isset($_POST['type']))
        {
            $type = $_POST['type'];
            if(!is_array($_POST['tag_ids']))
                $_POST['tag_ids']=array($_POST['tag_ids']);
            $model=$this->loadModel($id,array(
                    'with'=>array('Hotel_Items'),
                    'condition'=>'Hotel_Items.status=0 AND Hotel_Items.audit !=:audit AND Hotel_Items.c_id=:c_id',
                    'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
            ));
            $tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id    
            if($type=='yes'){
                //过滤之前有的
                $save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_items_hotel);                            
                $return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_items_hotel);
            }else
                $return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_items_hotel);        
            if($return)
            {
                if($type=='yes')
                    $this->log('项目(住)添加标签', ManageLog::admin,ManageLog::create);
                else
                    $this->log('项目(住)去除标签', ManageLog::admin,ManageLog::clear);
                echo 1;
            }else
                echo '操作过于频繁，请刷新页面从新选择！';
        }else
            echo '没有选中标签，请重新选择标签！';
    }
    
    /**
     * 删除
     * @param integer $id
     */
    public function actionDelete($id)
    {
        //获取项目类型
        $c_id=ItemsClassliy::getClass()->id;//住
        $this->_class_model='Items';
        if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
            $this->log('删除项目(住)',ManageLog::admin,ManageLog::delete);
            
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }
    
    /**
     * 还原
     * @param integer $id
     */
    public function actionRestore($id)
    {
        //获取项目类型
        $c_id=ItemsClassliy::getClass()->id;//住
        $this->_class_model='Items';
        if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
            $this->log('还原项目(住)',ManageLog::admin,ManageLog::update);
            
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }
    
    /**
     * 审核通过
     * @param integer $id
     */
    public function actionPass($id){
        //改变布局
        $this->layout='/layouts/column_right_audit';
        //查看是否需要审核
        $model=$this->loadModel($id,array(
                'with'=>array('Hotel_Items'=>array(
                        'condition'=>'Hotel_Items.status=0 AND Hotel_Items.audit=:audit AND t.c_id=:c_id',
                        'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
                ))
        ));
        $model->Hotel_Items->scenario='pass';
        $this->_Ajax_Verify($model->Hotel_Items,'items-form');

        if(isset($_POST['Items']))
        {
            $model->Hotel_Items->attributes=$_POST['Items'];
            $model->Hotel_Items->pub_time=time();
            $model->Hotel_Items->is_push=Items::push_init;//设置初始化
            $model->Hotel_Items->audit=Items::audit_pass;// 审核通过
            $transaction=$model->dbConnection->beginTransaction();
            if($model->Hotel_Items->validate()){
                try
                {
                    if($model->Hotel_Items->save(false)){
                        $audit=new AuditLog;
                        $audit->info=$model->Hotel_Items->push.'%'.$model->Hotel_Items->push_orgainzer.'%'.$model->Hotel_Items->push_store.'%'.$model->Hotel_Items->push_agent.'%';
                        $audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
                        $audit->audit_element=AuditLog::items_hotel;//记录 被审核的类型
                        $audit->element_id=$model->id;//记录 被审核id
                        $audit->audit=AuditLog::pass;//记录 审核通过
                        if($audit->save(false))
                            $return=$this->log('添加审核项目(住)记录分成比例',ManageLog::admin,ManageLog::create);
                        else
                            throw new Exception("添加审核日志错误");
                    }else
                        throw new Exception("审核通过保存错误");
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
                }
            }
            if(isset($return))
                $this->back();
        }
        $this->render('/tmm_items/_pass',array(
                'model'=>$model->Hotel_Items,
        ));
    }
    
    /**
     * 审核不通过
     * @param integer $id
     */
    public function actionNopass($id){
        //改变布局
        $this->layout='/layouts/column_right_audit';
        $model=new AuditLog;
        
        $model->scenario='create';
        //查看是否需要审核
        $model->Audit_Hotel=$this->loadModel($id,array(
                'with'=>array('Hotel_Items'=>array(
                        'condition'=>'Hotel_Items.status=0 AND Hotel_Items.audit=:audit AND t.c_id=:c_id',
                        'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
                ))
        ));
        $this->_Ajax_Verify($model,'audit-log-form');
    
        if(isset($_POST['AuditLog']))
        {
            $model->attributes=$_POST['AuditLog'];
            $model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
            $model->audit_element=AuditLog::items_hotel;//记录 被审核的
            $model->element_id=$model->Audit_Hotel->id;//记录 被审核id
            $model->audit=AuditLog::nopass;//记录 审核不通过
            if($model->validate()){
                $transaction=$model->dbConnection->beginTransaction();
                try
                {
                    if($model->save(false))
                    {
                        $model->Audit_Hotel->Hotel_Items->audit=Items::audit_nopass;//审核不通过
                        if($model->Audit_Hotel->Hotel_Items->save(false))
                            $return=$this->log('项目(住)审核不通过记录',ManageLog::admin,ManageLog::create);
                    }else
                        throw new Exception("添加项目(住)审核不通过日志错误");
                        
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
                }
            }
            if(isset($return))
                $this->back();
        }
        $this->render('/tmm_auditLog/_nopass',array(
                'model'=>$model,
        ));
    }

//     /**
//      * 彻底删除
//      * @param integer $id
//      */
//     public function actionClear($id)
//     {        
//         $model=$this->loadModel($id,array(
//                 'condition'=>'Hotel_Items.status=-1',
//                 'with'=>array(
//                         'Hotel_Items',
//                         'Hotel_ItemsImg',
//                         'Hotel_Fare'
//                 ),
//         ));
//         $transaction=$model->dbConnection->beginTransaction();
//         try
//         {
//             if($model->delete() && $model->Hotel_Items->delete())
//             {
//                 $this->upload_delete($model->Hotel_Items->map);
//                 foreach ($model->Hotel_ItemsImg as $model_img)
//                 {
//                     if($model_img->delete())
//                         $this->upload_delete($model_img->img);
//                     else
//                         throw new Exception("删除项目(住)图片记录出现错误");
//                 }
//                 foreach ($model->Hotel_Fare as $model_fare){
//                     if(! $model_fare->delete())
//                         throw new Exception("删除项目(住)价格记录出现错误");            
//                 }        
//                 $this->log('彻底删除项目(住)',ManageLog::admin,ManageLog::delete);
//             }else
//                 throw new Exception("添加项目(住)中出现记录错误");
//             $transaction->commit();
//         }
//         catch(Exception $e)
//         {
//             $transaction->rollBack();
//             $this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::delete,ErrorLog::rollback,__METHOD__);
//         }
//         if(!isset($_GET['ajax']))
//             $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//     }

    /**
     * 垃圾回收页
     */
    public function actionIndex()
    {
        $criteria=new CDbCriteria;
        $criteria->with=array(
                    'Hotel_Items'=>array(
                            'with'=>array(
                                    'Items_StoreContent'=>array('with'=>array('Content_Store')),
                                    'Items_Store_Manager',
                                    'Items_agent',
                                    'Items_area_id_p_Area_id'=>array('select'=>'name'),
                                    'Items_area_id_m_Area_id'=>array('select'=>'name'),
                                    'Items_area_id_c_Area_id'=>array('select'=>'name'),
                                    //'Items_ItemsClassliy',
                            )),
                    'Hotel_ItemsClassliy',
            );
        $criteria->addColumnCondition(array('Hotel_Items.status'=>-1));
        $model=new Hotel;    
        $this->render('index',array(
            'model'=>$model->search($criteria),
        ));
    }
    
    /**
     *管理页
     */
    public function actionAdmin()
    {
        $model=new Hotel('search');
        $model->unsetAttributes();  // 删除默认属性
        $model->Hotel_Items=new Items('search');
        $model->Hotel_Items->unsetAttributes();  // 删除默认属性
        if(isset($_GET['Hotel']))
            $model->attributes=$_GET['Hotel'];
        if(isset($_GET['Items']))
            $model->Hotel_Items->attributes=$_GET['Items'];
        
        $this->render('admin',array(
            'model'=>$model,
        ));
    }
    
    /**
     * 下线
     * @param integer $id
     */
    public function actionDisable($id){
        //获取项目类型
        $c_id=ItemsClassliy::getClass()->id;//住    
        $this->_class_model='Items';    
        if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
            $this->log('禁用项目(住)',ManageLog::admin,ManageLog::update);            
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }
    
    /**
     * 激活
     * @param integer $id
     */
    public function actionStart($id){
        //获取项目类型
        $c_id=ItemsClassliy::getClass()->id;//住
        $this->_class_model='Items';
        if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND `audit`=:audit',array(':c_id'=>$c_id,':audit'=>Items::audit_pass))->updateByPk($id,array('status'=>1)))
             $this->log('激活项目(住)',ManageLog::admin,ManageLog::update);
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
    }
    
    /**
     * 提交审核
     * @param unknown $id
     */
    public function actionConfirm($id)
    {
        //获取项目类型
        $c_id=ItemsClassliy::getClass()->id;//点
        $this->_class_model='Items';
        if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND audit=:audit',array(':c_id'=>$c_id,':audit'=>Items::audit_draft))->updateByPk($id,array('audit'=>Items::audit_pending)))
            $this->log('提交项目(住)审核',ManageLog::admin,ManageLog::update);
    
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));    
    }

}
