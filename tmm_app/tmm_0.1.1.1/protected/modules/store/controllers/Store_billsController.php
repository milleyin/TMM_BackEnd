<?php
/**
 * 商家收入控制器
 * @author Moore Mo
 * Class Store_BillsController
 */
class Store_BillsController extends StoreMainController
{
    public $_class_model = 'Bills';
    /**
     * 我的收入列表（主商家，子帐号）--最近七天收入
     */
    public function actionIndex()
    {
        $this->_class_model = 'StoreUser';
        $user_model = $this->loadModel(Yii::app()->store->id,'status=1 AND p_id=0');

        $this->_class_model = 'Bills';
        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(array(
            '`t`.`status`'=>1,
        ));
        $criteria->addCondition('`t`.`store_id`=:store_id');
        $criteria->params[':store_id'] = Yii::app()->store->id;
        $criteria->addBetweenCondition('`t`.`add_time`', strtotime("-7 day"), time());
        $criteria->select = "`t`.*,sum(`t`.`items_money_store`) as day_money";
        $criteria->group = '`t`.`manager_id`,FROM_UNIXTIME(`t`.`add_time`, "%Y%m%d")';
        $criteria->order = '`t`.`add_time`';

        $models = Bills::model()->findAll($criteria);

        $manager_arr =array();
        // 主商家收入情况
        $return['main']['info'] = array(
            'value' => $user_model->id,
            'phone' => $user_model->phone,
            'total_money' => 0.00
        );
        $total_money = 0.00;
        foreach ($models as $model)
        {
            // 主商家
            if ($model->manager_id == Yii::app()->store->id)
            {
                $return['main']['main_list_data'][] = array(
                    'add_time' => date('Y-m-d', $model->add_time),
                    'day_money' => $this->money_floor($model->day_money),
                );
                $total_money += $model->day_money;
            }
            else
            {
                // 子帐号
                $manager_arr[] = $model->manager_id;
            }
        }
        // 商家近七天总收入
        $return['main']['info']['total_money'] = $this->money_floor($total_money);
        // 去重
        $manager_arr = array_unique($manager_arr);
        if (!empty($manager_arr)) {
            foreach($manager_arr as $m_id)
            {
                $son_total_money = 0.00;
                $this->_class_model = 'StoreUser';
                $user_model = $this->loadModel($m_id,'status=1');
                $return['son'][$m_id]['info'] = array(
                    'value' => $user_model->id,
                    'phone' => $user_model->phone,
                    'total_money' => 0.00
                );
                foreach ($models as $model) {
                    // 子帐号
                    if ($model->manager_id == $m_id)
                    {
                        $return['son'][$m_id]['son_list_data'][] = array(
                            'add_time' => date('Y-m-d', $model->add_time),
                            'day_money' => $this->money_floor($model->day_money),
                        );
                        $son_total_money += $model->day_money;
                    }
                }
                $return['son'][$m_id]['info']['total_money'] = $this->money_floor($son_total_money);
            }
        } else {
            $return['son'] = array();
        }

        $return['son'] = array_values($return['son']);


        if(empty($return['main']['main_list_data']))
        {
            $return['main']=array();
            $return['main']['null']='暂无数据！';
        }
        if(empty($return['son']))
        {
            $return['son']=array();
            $return['son']['null']='暂无数据！';
        }

        $this->send($return);
    }

    /**
     * 我的收入列表（子帐号）--最近七天
     * TODO
     */
    public function actionSon_index()
    {
        $this->_class_model = 'StoreUser';
        $user_model = $this->loadModel(Yii::app()->store->id,'status=1 AND p_id !=0');

        $this->_class_model = 'Bills';
        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(array(
            '`t`.`status`'=>1,
        ));
        $criteria->addBetweenCondition('`t`.`add_time`', strtotime("-7 day"), time());
        $criteria->addCondition('`t`.`store_id`=:store_id AND `t`.`manager_id` =:manager_id');
        $criteria->params[':store_id'] = $user_model->p_id;
        $criteria->params[':manager_id'] = Yii::app()->store->id;

        $criteria->select = "`t`.*, sum(`t`.`items_money_store`) as day_money";
        $criteria->group = 'FROM_UNIXTIME(`t`.`add_time`, "%Y%m%d")';
        $criteria->order = '`t`.`add_time`';

        $models = Bills::model()->findAll($criteria);

        $return = array();
        $return['info'] = array(
            'value' => $user_model->id,
            'phone' => $user_model->phone,
            'total_money' => 0.00
        );
        // 子帐号收入情况
        $total_money = 0.00;
        foreach ($models as $model) {
            $return['list_data'][] = array(
                'add_time' => date('Y-m-d', $model->add_time),
                'day_money' =>$this->money_floor($model->day_money),
            );
            $total_money += $model->day_money;
        }

        // 子帐号近七天总收入
        $return['info']['total_money'] = $this->money_floor($total_money);

        if(empty($return['list_data']))
        {
            unset($return['info']);
            $return['list_data']=array();
            $return['null']='暂无数据！';
        }

        $this->send($return);
    }

    /**
     * 我的收入明细--产生订单的项目列表
     * TODO--
     */
    public function actionItems_Index()
    {
        $this->_class_model = 'StoreUser';
        $this->loadModel(Yii::app()->store->id,'status=1');

        $this->_class_model = 'Bills';
        $criteria = new CDbCriteria;
        $criteria->with = array(
        );

        $criteria->addColumnCondition(array(
            '`t`.`status`'=>1,
        ));
        $criteria->addBetweenCondition('`t`.add_time', strtotime(time()) - (3600*24 -7), strtotime(time()));
        $criteria->addCondition('`t`.`store_id`=:store_id AND `t`.`manager_id`=:manager_id');
        $criteria->params[':store_id'] = Yii::app()->store->id;
        $criteria->params[':manager_id'] = Yii::app()->store->id;

        $criteria->select = "`t`.*,count(`t`.`id`) as items_count, sum(`t`.`items_money_store`) as items_count_money";
        $criteria->group = '`t`.`items_id`';
        $criteria->order = '`t`.`up_time` desc';

        $count = Bills::model()->count($criteria);

        $return = array();
        //分页设置
        $return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['store_items'], Yii::app()->params['app_api_domain']);
        //根据条件查询
        $models = Bills::model()->findAll($criteria);
        //分页数据
        $domain = Yii::app()->params['app_api_domain'];
        foreach ($models as $model) {
            $return['list_data'][] = array(
                'items_value' => $model->items_id,
                'items_name' => CHtml::encode($model->items_name),
                //'items_img' => !empty($model->items_img) ? Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->items_img),'.') : '',
                'items_count' => $model->items_count,
                // 取两位小数，不四舍五入的方法 floor(num*100)/100,
                'items_count_money' =>$this->money_floor($model->items_count_money),
            );
        }

        if(empty($return['list_data']))
        {
            $return['list_data']=array();
            $return['null']='暂无数据！';
        }

        $this->send($return);
    }

    /**
     * 项目的订单
     */
    public function actionOrder_index($id)
    {
        $this->_class_model = 'StoreUser';
        $this->loadModel(Yii::app()->store->id,'status=1');

        $this->_class_model = 'Bills';
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'Bills_Order',
            'Bills_OrderShops',
        );

        $criteria->addColumnCondition(array(
            't.status'=>1,
        ));
        $criteria->addCondition('`t`.`items_id`=:items_id AND (`t`.`store_id`=:store_id OR `t`.`manager_id`=:manager_id)');
        $criteria->params[':items_id'] = $id;
        $criteria->params[':store_id'] = Yii::app()->store->id;
        $criteria->params[':manager_id'] = Yii::app()->store->id;

        $criteria->select = "`t`.*,count(`t`.`id`) as items_count";
        $criteria->group = '`t`.`order_id`';
        $criteria->order = '`t`.`up_time` desc';

        $count = Bills::model()->count($criteria);

        $return = array();
        //分页设置
        $return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['store_items'], Yii::app()->params['app_api_domain']);
        //根据条件查询
        $models = Bills::model()->findAll($criteria);
        //分页数据
        $return['list_data'] = $this->list_data($models, Yii::app()->params['app_api_domain']);

        if(empty($return['list_data']))
        {
            $return['list_data']=array();
            $return['null']='暂无数据！';
        }

        $this->send($return);
    }

    /**
     * 商家收入中的订单信息分页数据
     * @param $models
     * @param $domain
     * @return array
     */
    public function list_data($models,$domain)
    {
        $return=array();

        foreach ($models as $model)
        {
            // 订单基本信息
            $return[] = array(
                'value' => $model->order_id,
                'order_no' => $model->order_no,
                'shops_name' => CHtml::encode($model->shops_name),
                'income' => $model->items_money_store * $model->total,
                'items_count' => $model->items_count,
                'shops_list_img' => !empty($model->Bills_OrderShops->shops_list_img) ? Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Bills_OrderShops->shops_list_img),'.') : '',
                'shops_type' => array(
                    'value' => $model->shops_c_id,
                    'name' => $model->shops_c_name,
                ),
                'order_status' => array(
                    'value' => $model->Bills_Order->order_status,
                    'name' => Order::$_order_status[$model->Bills_Order->order_status],
                )
            );
        }
        return $return;
    }

    /**
     * 帐目订单中的订单详情
     * @param $id
     */
    public function actionOrder_view($id)
    {
        $this->_class_model = 'StoreUser';
        $this->loadModel(Yii::app()->store->id,'status=1');

        $criteria =new CDbCriteria;
        $criteria->with=array(
            'Bills_Order',
            'Bills_OrderOrganizer',
            'Bills_OrderRetinue',
            'Bills_OrderItems'=>array(
                'with'=>array(
                    'OrderItems_OrderItemsFare',
                    'OrderItems_ItemsClassliy',
                )
            ),
        );
        $criteria->addColumnCondition(array(
            't.status'=>1,
            'Bills_Order.status'=>Order::status_yes,//有效的订单
        ));

        $criteria->addCondition('(`Bills_OrderItems`.`store_id`=:store_id OR `Bills_OrderItems`.`manager_id`=:manager_id)');
        $criteria->params[':store_id'] = Yii::app()->store->id;
        $criteria->params[':manager_id'] = Yii::app()->store->id;


        $this->_class_model='Bills';
        $model=Bills::model()->find('`order_id`=:order_id',array(':order_id'=>$id), $criteria);
        $return = array();

        if($model->Bills_Order->order_type==Order::order_type_group)
        {
            if(isset($model->Bills_OrderOrganizer->group_group_time) && $model->Bills_OrderOrganizer->group_group_time != '')
                $return['go_time']=date('Y-m-d',$model->Bills_OrderOrganizer->group_group_time);
            else
                $return['go_time']='未定';


        }
        elseif($model->Bills_Order->order_type==Order::order_type_thrand)
        {
            $return['go_time']=date('Y-m-d',$model->Bills_Order->go_time);


        }
        elseif ($model->Bills_Order->order_type==Order::order_type_dot)		//点的下单
        {
            $return['go_time']=date('Y-m-d',$model->Bills_Order->go_time);	//出游时间
            $return['order_price']=array(
                'name'=>'订单总计',
                'value'=>$model->Bills_Order->order_price,
            );//出游时间
            $return['user_price_fact']=array(
                'name'=>'服务费/人',
                'value'=>$model->Bills_Order->user_price_fact,
            );//出游时间
            $return['user_go_count']=array(
                'name'=>'出游人数',
                'value'=>$model->Bills_Order->user_go_count,
            );
            //判断数据是否存在
            if( !isset($model->Bills_OrderItems) || empty($model->Bills_OrderItems) || !is_array($model->Bills_OrderItems))
                $this->send_error(DATA_NOT_SCUSSECS);
            if( !isset($model->Bills_OrderRetinue) || empty($model->Bills_OrderRetinue) || !is_array($model->Bills_OrderRetinue))
                $this->send_error(DATA_NOT_SCUSSECS);
            //随行人员
            foreach ($model->Bills_OrderRetinue as $Bill_OrderRetinue)
            {
                $return['retinue'][]=array(
                    'is_main'=>$Bill_OrderRetinue->is_main,
                    'name'=>$Bill_OrderRetinue->retinue_name,
                    'value'=>$Bill_OrderRetinue->retinue_id,
                    'gender'=>$Bill_OrderRetinue->retinue_gender,
                    'phone'=>$Bill_OrderRetinue->retinue_phone,
                    'identity'=>$Bill_OrderRetinue->retinue_identity,
                    'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/retinue/view',array('id'=>$Bill_OrderRetinue->retinue_id)),
                );
            }
            //项目 价格
            foreach ($model->Bills_OrderItems as $key=>$Bill_OrderItems)
            {
                if(!isset($Bill_OrderItems->OrderItems_ItemsClassliy) || is_array($Bill_OrderItems->OrderItems_ItemsClassliy))
                    $this->send_error(DATA_NOT_SCUSSECS);
                $return['items_fare'][$key]['name']=$Bill_OrderItems->items_name;
                $return['items_fare'][$key]['value']=$Bill_OrderItems->items_id;
                $return['items_fare'][$key]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$Bill_OrderItems->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$Bill_OrderItems->items_id));
                $return['items_fare'][$key]['barcode']=array(
                    'is_barcode'=>$Bill_OrderItems->is_barcode,
                    'value'=>$Bill_OrderItems->id,
                    'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/barcode',array('id'=>$Bill_OrderItems->id)),
                );
                $return['items_fare'][$key]['classliy']=array(
                    'name'=>$Bill_OrderItems->items_c_name,
                    'value'=>$Bill_OrderItems->items_c_id,
                );
                if( !isset($Bill_OrderItems->OrderItems_OrderItemsFare) || empty($Bill_OrderItems->OrderItems_OrderItemsFare) || !is_array($Bill_OrderItems->OrderItems_OrderItemsFare))
                    $this->send_error(DATA_NOT_SCUSSECS);
                foreach ($Bill_OrderItems->OrderItems_OrderItemsFare as $OrderItems_OrderItemsFare)
                {
                    // 价格信息
                    $return['items_fare'][$key]['fare'][]=array(
                        'name' => $OrderItems_OrderItemsFare->fare_name,
                        'info' => $OrderItems_OrderItemsFare->fare_info,
                        'number' => $OrderItems_OrderItemsFare->number,
                        'price' => $OrderItems_OrderItemsFare->fare_price,
                        'count'=>$OrderItems_OrderItemsFare->total,
                    );
                }
            }

            // 订单基本信息
            $return['order_no'] = $model->order_no;
            $return['add_time'] = date('Y-m-d H:m:s', $model->Bills_Order->add_time);
            $return['up_time'] = date('Y-m-d H:m:s', $model->Bills_Order->up_time);
            $return['pay_time'] = date('Y-m-d H:m:s', $model->Bills_Order->pay_time);
            //支付类型
            $return['pay_type']=Yii::app()->params['pay_type'];
            //状态
            $return['status']=array(
                'pay_type'=>array(
                    'name'=>Order::$_pay_type[$model->Bills_Order->pay_type],
                    'value'=>$model->Bills_Order->pay_type,
                ),
                'order_status'=>array(
                    'name'=>Order::$_order_status[$model->Bills_Order->order_status],
                    'value'=>$model->Bills_Order->order_status,
                ),
                'status_go'=>array(
                    'name'=>Order::$_status_go[$model->Bills_Order->status_go],
                    'value'=>$model->Bills_Order->order_status,
                ),
                'centre_status'=>array(
                    'name'=>Order::$_centre_status[$model->Bills_Order->centre_status],
                    'value'=>$model->Bills_Order->centre_status,
                ),
                'pay_status'=>array(
                    'name'=>Order::$_pay_status[$model->Bills_Order->pay_status],
                    'value'=>$model->Bills_Order->pay_status,
                ),
                'order_type'=>array(
                    'name'=>Order::$_order_type[$model->Bills_Order->order_type],
                    'value'=>$model->Bills_Order->order_type,
                ),
            );
        }else
            $this->send(DATA_NOT_SCUSSECS);

        $this->send($return);
    }
}