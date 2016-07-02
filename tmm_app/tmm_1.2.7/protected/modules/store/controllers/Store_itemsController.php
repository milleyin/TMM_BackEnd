<?php
/**
 * 商家项目控制器
 * @author Moore Mo
 * Class Store_itemController
 */
class Store_itemsController extends StoreMainController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model='Items';

    /**
     * 商家的项目列表
     */
    public function actionIndex()
    {
        $this->_class_model = 'StoreUser';
        $this->loadModel(Yii::app()->store->id,'status=1');

        $this->_class_model = 'Items';
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'Items_ItemsClassliy',
            'Items_ItemsImg',
        );
        $criteria->addColumnCondition(array(
            't.status'=>Items::status_online,
            't.audit'=>Items::audit_pass,
        ));
        $criteria->addCondition('(`t`.`store_id`=:store_id OR `t`.`manager_id`=:manager_id)');
        $criteria->params[':store_id'] = Yii::app()->store->id;
        $criteria->params[':manager_id'] = Yii::app()->store->id;

        $criteria->order = 't.pub_time desc';

        $count = Items::model()->count($criteria);

        $return = array();
        //分页设置
        $return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['store_items'], Yii::app()->params['app_api_domain']);
        //根据条件查询
        $models = Items::model()->findAll($criteria);
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
     * 商家项目信息分页数据
     * @param $models
     * @param $domain
     * @return array
     */
    public function list_data($models,$domain)
    {
        $return=array();

        foreach ($models as $model)
        {
            // 项目基本信息
            $return[] = array(
                'value' => $model->id,
                'link' => $domain.Yii::app()->createUrl('/store/store_'.$model->Items_ItemsClassliy->admin.'/view',array('id'=>$model->id)),
                'name' => CHtml::encode($model->name),
                'info'=>CHtml::encode($model->info),
                'item_litimg' => !empty($model->Items_ItemsImg['0']->img) ? Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Items_ItemsImg['0']->img),'.') : '',
                'classliy'=>array(
                    'name'=>CHtml::encode($model->Items_ItemsClassliy->name),
                    'value'=>$model->Items_ItemsClassliy->id,
                ),
            );
        }
        return $return;
    }
}