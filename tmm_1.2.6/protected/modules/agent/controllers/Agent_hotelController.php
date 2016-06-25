<?php

/**
 * @author Moore Mo
 * 项目（住）
 * Class Agent_hotelController
 */
class Agent_hotelController extends AgentController
{
    /**
     * 默认操作数据模型
     * @var string
     */
    public $_class_model = 'Hotel';

    /**
     * 创建项目（住）第二部
     * @param $id
     * @throws CHttpException
     */
    public function actionCreate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl . '/css/admin/ext/uploadify/jquery.uploadify.min.js');

        $model = new Hotel;
        $model->Hotel_Items = new Items;
        $model->Hotel_ItemsImg = new ItemsImg;

        $model->Hotel_Items->scenario = 'agent_create_hotel';
        if (isset($_POST['Fare']) && is_array($_POST['Fare'])) {
            $number = count($_POST['Fare']);
            if ($number > Yii::app()->params['items_fare_number'])
                $number = Yii::app()->params['items_fare_number'];
            $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel', $number);
        } else
            $model->Hotel_Fare = $this->new_modes('Fare', 'create_hotel');
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Hotel_Fare, array($model->Hotel_Items)), 'hotel-form');

        $this->_class_model = 'StoreUser';
        $model->Hotel_Items->Items_Store_Manager = $this->loadModel($id, array(
            'condition' => '`status`=1 AND `agent_id`=:agent_id',
            'params' => array(':agent_id' => Yii::app()->agent->id),
        ));
        //获取项目类型
        $this->_class_model = 'Hotel';
        $model->Hotel_Items->c_id = ItemsClassliy::getClass()->id;//住

        if (isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare']) == count($model->Hotel_Fare)) {
            $model->Hotel_Items->attributes = $_POST['Items'];
            //赋值
            $model->Hotel_Items->agent_id = Yii::app()->agent->id;
            if ($model->Hotel_Items->Items_Store_Manager->p_id == 0)
                $model->Hotel_Items->store_id = $model->Hotel_Items->Items_Store_Manager->id;
            else
                $model->Hotel_Items->store_id = $model->Hotel_Items->Items_Store_Manager->p_id;
            $model->Hotel_Items->manager_id = $model->Hotel_Items->Items_Store_Manager->id;
            //上传图片
            $this->_upload = Yii::app()->params['uploads_items_map'];
            $uploads = array('map');
            $files = $this->upload($model->Hotel_Items, $uploads);
            //图片验证
            $Items_img_validate = $this->upload_error($model->Hotel_Items, $files, $uploads);
            if ($Items_img_validate)
                $Items_validate = $model->Hotel_Items->validate();    //项目字段验证
            else
                $Items_validate = false;
            //项目价格验证
            $Fare_validate = $this->models_validate($model->Hotel_Fare);

            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp'])) {
                //验证图片
                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                $filename = $this->_upload . date('Ymd') . '/' . current($_POST['ItemsImg']['tmp']);
                if (!file_exists($filename)) {
                    $model->Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                    $img_validate = false;
                } elseif (count($_POST['ItemsImg']['tmp']) > Yii::app()->params['items_image_number']) {
                    $model->Hotel_ItemsImg->addError('tmp', '概况图 不可超过' . Yii::app()->params['items_image_number'] . '张');
                    $img_validate = false;
                } else
                    $img_validate = true;
            } else {
                $model->Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                $img_validate = false;
            }

            //提前验证都通过
            if ($Fare_validate && $Items_validate && $Items_img_validate && $img_validate) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    $model->Hotel_Items->status = Items::status_offline;//创建的项目是下线状态
                    $model->Hotel_Items->audit = items::audit_draft;//创建的默认未提交
                    //处理图片链接
                    $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                    if ($model->Hotel_Items->save(false))//保存项目主要表
                    {
                        $model->id = $model->Hotel_Items->id;
                        $model->c_id = $model->Hotel_Items->c_id;
                        if (!$model->save(false))                        //保存项目附表
                            throw new Exception("添加项目(住)记录错误");
                        else {
                            //价格
                            foreach ($model->Hotel_Fare as $model_fare) {
                                $model_fare->store_id = $model->Hotel_Items->store_id;
                                $model_fare->agent_id = Yii::app()->agent->id;
                                $model_fare->item_id = $model->Hotel_Items->id;
                                $model_fare->c_id = $model->Hotel_Items->c_id;
                                if (!$model_fare->save(false))
                                    throw new Exception("添加项目(住)价格记录错误");
                            }
                            //图片
                            foreach ($_POST['ItemsImg']['tmp'] as $name) {
                                //保存上传的项目图片多张
                                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                if (file_exists($filename)) {
                                    $this->_upload = Yii::app()->params['uploads_items_hotel'];
                                    if (!$this->items_img_save($model->Hotel_Items, $filename))//保存图片
                                        throw new Exception("添加项目(住)图片记录错误");
                                }
                            }
                            $this->upload_save($model->Hotel_Items, $files);
                            $return = $this->log('添加项目(住)', ManageLog::agent, ManageLog::create);
                        }
                    } else
                        throw new Exception("添加项目(住)主要记录错误");
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create, ErrorLog::rollback, __METHOD__);
                }
                if (isset($return))
                    // 选择服务
                    $this->redirect(array('/agent/agent_hotel/wifi', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 更新项目住
     */
    public function actionUpdate($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl . '/css/admin/ext/uploadify/jquery.uploadify.min.js');

        $model = $this->loadModel($id, array(
            'with' => array(
                'Hotel_Items' => array(
                    'with' => array(
                        'Items_StoreContent' => array('with' => 'Content_Store'),
                    )
                ),
                'Hotel_ItemsImg',
                'Hotel_Fare',
            ),
            'condition' => '`Content_Store`.status=1 AND `Hotel_Items`.`status`=0 AND `t`.`c_id`=:c_id AND `Hotel_Items`.`audit` !=:audit AND `Hotel_Items`.`agent_id`=:agent_id',
            'params' => array(':c_id' => ItemsClassliy::getClass()->id, ':audit' => Items::audit_pending, ':agent_id' => Yii::app()->agent->id),
        ));

        $fare_count = count($model->Hotel_Fare);
        $fare_ids = $this->listData($model->Hotel_Fare, 'id');
        $items_img_ids = $this->listData($model->Hotel_ItemsImg, 'id');

        if (isset($_POST['Fare']) && is_array($_POST['Fare'])) {
            $number = count($_POST['Fare']);
            if ($number > Yii::app()->params['items_fare_number'])
                $number = Yii::app()->params['items_fare_number'];
            //更新models
            $model->Hotel_Fare = $this->update_models($model->Hotel_Fare, $number, 'update_hotel');
        }
        $this->set_scenarios($model->Hotel_Fare, 'update_hotel');
        $model->Hotel_Items->scenario = 'agent_update_hotel';

        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Hotel_Fare, array($model->Hotel_Items)), 'hotel-form');
        //获取项目类型
        $this->_class_model = 'Hotel';
        $model->Hotel_Items->c_id = ItemsClassliy::getClass()->id;//吃

        if (isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare']) == count($model->Hotel_Fare)) {
            //上传图片
            $this->_upload = Yii::app()->params['uploads_items_map'];
            $uploads = array('map');
            $data = $this->upload_save_data($model->Hotel_Items, $uploads);//保存原来的

            $files = $this->upload($model->Hotel_Items, $uploads);
            
            $model->Hotel_Items->attributes=$_POST['Items'];
            //项目字段验证
            $Items_validate = $model->Hotel_Items->validate();
            $old_path = $this->upload_update_data($model->Hotel_Items, $data, $files);//还原原来的值
            //项目价格验证
            $Fare_validate = $this->models_validate($model->Hotel_Fare);

            //获取图片id
            $img_ids = $this->array_listData($_POST['ItemsImg'], 'id');
            if (!empty($img_ids))
                $img_ids = ItemsImg::filter_id($model->id, $img_ids);//过滤 id 剩下的id
            $img_path_array = ItemsImg::filter_id($model->id, '', false, array('id' => 'img'));//获取所有的返回属性组成的数组
            if (!empty($model->Hotel_ItemsImg) && isset($model->Hotel_ItemsImg[0]))
                $Hotel_ItemsImg = $model->Hotel_ItemsImg[0];
            else {
                $model->Hotel_ItemsImg = array(new ItemsImg);
                $Hotel_ItemsImg = $model->Hotel_ItemsImg[0];
            }
            if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp'])) {
                if ((count($img_ids) + count($_POST['ItemsImg']['tmp'])) > Yii::app()->params['items_image_number']) {
                    $Hotel_ItemsImg->addError('tmp', '概况图 不可超过' . Yii::app()->params['items_image_number'] . '张');
                    $items_img_validate = false;//false;
                } elseif ((count($img_ids) + count($_POST['ItemsImg']['tmp'])) == 0) {
                    $Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                    $items_img_validate = false;//false;
                } else
                    $items_img_validate = true;
            } elseif (count($img_ids) > Yii::app()->params['items_image_number']) {
                $Hotel_ItemsImg->addError('tmp', '概况图 不可超过' . Yii::app()->params['items_image_number'] . '张');
                $items_img_validate = false;//false;
            } elseif (count($img_ids) == 0) {
                $Hotel_ItemsImg->addError('tmp', '概况图 不可空白');
                $items_img_validate = false;//false;
            } else
                $items_img_validate = true;

            //提前验证都通过
            if ($Fare_validate && $Items_validate && $items_img_validate) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    $model->Hotel_Items->audit = Items::audit_draft;//修改未提交
                    //处理图片链接
                    $model->Hotel_Items->content = $this->admin_img_replace($model->Hotel_Items->content);
                    if ($model->Hotel_Items->save(false))//保存项目主要表
                    {
                        foreach ($model->Hotel_Fare as $model_fare) {
                            $model_fare->store_id = $model->Hotel_Items->store_id;
                            $model_fare->agent_id = Yii::app()->agent->id;
                            $model_fare->item_id = $model->id;
                            $model_fare->c_id = $model->Hotel_Items->c_id;
                            if (!$model_fare->save(false))
                                throw new Exception("添加项目(住)价格记录错误");
                            $fare_count--;
                        }
                        if ($fare_count > 0) {
                            rsort($fare_ids);
                            for ($j = 0; $j < $fare_count; $j++) {
                                Fare::model()->deleteByPk($fare_ids[$j]);
                            }
                        }
                        if (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp'])) {
                            foreach ($_POST['ItemsImg']['tmp'] as $name) {
                                //保存上传的项目图片多张
                                $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
                                $filename = $this->_upload . date('Ymd') . '/' . $name;
                                if (file_exists($filename)) {
                                	$this->_upload = Yii::app()->params['uploads_items_hotel'];
                                    if (!$this->items_img_save($model->Hotel_Items, $filename))//保存图片
                                        throw new Exception("添加项目(住)图片记录错误");
                                }
                            }
                        }
                        foreach ($items_img_ids as $items_img_id) {
                            if (!in_array($items_img_id, $img_ids)) {
                                ItemsImg::model()->deleteByPk($items_img_id);
                                $this->upload_delete($img_path_array[$items_img_id]);
                            }
                        }
                        $this->upload_save($model->Hotel_Items, $files);
                        $this->upload_delete($old_path);//删除原来的
                        $return = $this->log('修改项目(住)', ManageLog::agent, ManageLog::update);
                    } else
                        throw new Exception("修改项目(住)主要记录错误");
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::update, ErrorLog::rollback, __METHOD__);
                }
                if (isset($return))
                    // 选择服务
                    $this->redirect(array('/agent/agent_hotel/wifi', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * 上传图成功 删除
     */
    public function actionUploads()
    {
        $this->_upload = Yii::app()->params['uploads_items_tmp_hotel'];
        if (isset($_POST['file_name'])) {
            $filename = $this->_upload . date('Ymd') . '/' . $_POST['file_name'];
            if (file_exists($filename))
                echo unlink($filename);
            else
                echo 0;
            Yii::app()->end();
        }
        $model = new ItemsImg;
        $model->scenario = 'uploads';
        $uploads = array('tmp');
        if ($this->upload_images($model, $uploads, true))
            echo json_encode(array('img_name' => basename($model->tmp), 'litimg' => $this->litimg_path($model->tmp, Yii::app()->params['litimg_pc'])));
        else
            echo json_encode(array('img_name' => 'none'));
        Yii::app()->end();
    }

    /**
     * 查看项目（住）详情
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

        $model = $this->loadModel($id, array(
            'with' => array(
                'Hotel_Items' => array(
                    'with' => array(
                        'Items_agent',
                        'Items_StoreContent' => array('with' => array('Content_Store')),
                        'Items_Store_Manager',
                        'Items_area_id_p_Area_id' => array('select' => 'name'),
                        'Items_area_id_m_Area_id' => array('select' => 'name'),
                        'Items_area_id_c_Area_id' => array('select' => 'name'),
                    )),
                'Hotel_ItemsClassliy',
                'Hotel_ItemsWifi' => array('with' => array('ItemsWifi_Wifi')),
                'Hotel_Fare',
                'Hotel_ItemsImg',
            ),
        	// 查看自己 除删除  查看别人 (上线，审核通过)
            'condition' => '(`Hotel_Items`.`status`>=0 AND `Hotel_Items`.`agent_id`=:agent_id) OR (`Hotel_Items`.`status`=1 AND `Hotel_Items`.`audit`=:audit AND `Hotel_Items`.`agent_id`!=:agent_id)',
        	'params'=>array(
                    ':agent_id'=>Yii::app()->agent->id,
        			':audit'=>Items::audit_pass,	
             ),
        ));
        // 标签
        $model->Hotel_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_hotel, $id);

        $this->render('view', array('model' => $model));
    }

    /**
     * 项目（住）选择服务
     * @param $id
     * @throws CHttpException
     */
    public function actionWifi($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

        $items_classliy = ItemsClassliy::getClass();//住

        $this->_class_model = 'Items';
        $model = $this->loadModel($id, array(
            'with' => array(
                'Items_ItemsClassliy',
                'Items_Hotel',
            ),
            'condition' => '`t`.`c_id`=:c_id AND `t`.`status`=0 AND `t`.`audit`=:audit AND `t`.`agent_id`=:agent_id',
            'params' => array(':c_id' => $items_classliy->id, ':audit' => Items::audit_draft, ':agent_id' => Yii::app()->agent->id),
        ));

        if (isset($_POST['Items']['id']) && $_POST['Items']['id'] == $id) 
            $this->redirect(array('/agent/agent_items/create_3', 'id' => $model->id));
        
		//获取wifi
        $model->Items_Hotel->Hotel_ItemsWifi = ItemsWifi::get_select_wifi($id);
        
        $wifi_model = new Wifi;
        $this->render($model->add_time == $model->up_time ? 'create_wifi' : 'update_wifi', array(
            'model' => $model,
            'wifi_model' => $wifi_model->search_wifi(true),
        ));
    }

    /**
     * 更新wifi
     * @param $id
     * @throws CHttpException
     */
    public function actionUpwifi($id)
    {
        if (isset($_POST['wifi_ids']) && !is_array($_POST['wifi_ids']) && $_POST['wifi_ids'] !== '' && isset($_POST['type'])) {
            $type = $_POST['type'];
            $model = $this->loadModel($id, array(
                'with' => array('Hotel_Items'),
                'condition' => 'Hotel_Items.status=0 AND Hotel_Items.audit=:audit',
                'params' => array(':audit' => Items::audit_draft),
            ));
            $wifi_ids = Wifi::filter_wifi($_POST['wifi_ids']);//安全过滤tags id
            if ($type == 'yes') {
                //过滤之前有的
                $wifi_ids = ItemsWifi::not_select_wifi($wifi_ids, $id);
                $return = ItemsWifi::select_wifi_save($wifi_ids, $model->Hotel_Items);
            } else
                $return = ItemsWifi::select_wifi_delete($wifi_ids, $id);
            if ($return) {
                if ($type == 'yes')
                    $this->log('项目(住)添加酒店环境', ManageLog::agent, ManageLog::create);
                else
                    $this->log('项目(住)去除酒店环境', ManageLog::agent, ManageLog::clear);
                echo 1;
            } else
                echo '操作过于频繁，请刷新页面从新选择！';
        } else
            echo '没有选中标签，请重新选择标签！';
    }
}