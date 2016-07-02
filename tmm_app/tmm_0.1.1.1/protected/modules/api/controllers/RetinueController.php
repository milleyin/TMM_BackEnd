<?php
/**
 * 随行人员控制器
 * @author Moore Mo
 * Class RetinueController
 */
class RetinueController extends ApiController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model = 'Retinue';

    /**
     * 随行人员详情
     * @param $id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id,array(
                'condition' => '`t`.`user_id`=:user_id AND t.status=:status',
                'params' => array(
                    ':user_id' => Yii::app()->api->id,
                    ':status' => 1
                )
            )
        );
        $return = array();
        if (! empty($model)) {
            $return['value'] = $model->id;
            $return['name'] = $model->name;
            $return['identity'] = $model->identity;
            $return['phone'] = $model->phone;
            $return['email'] = $model->email;
            $return['is_main'] = $model->is_main;
            if ($model->is_main == Retinue::is_main) {
                $return['update_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/update_main', array('id'=> $model->id));
            } else {
                $return['delete_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/delete', array('id'=> $model->id));
                $return['update_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/update', array('id'=> $model->id));
            }
        }

        $this->send($return);
    }

    /**
     * 随行人员列表
     * @param int $type 0普通随行人员，1查主要联系人
     */
    public function actionIndex($type=0)
    {
        //  $type 0普通随行人员，1查主要联系人
        if (! in_array($type, array(0,1))) {
            $this->send_error(METHOD_NOT_ALLOWED);
        }

        $return = array();

        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(array(
            '`user_id`'=>Yii::app()->api->id,
            '`status`'=>1,
            '`is_main`'=>$type,
        ));
        // 统计数目
        $count = Retinue::model()->count($criteria);
        // 分页设置
        $return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['retinue'], Yii::app()->params['app_api_domain']);
        // 根据条件查询
        $models = Retinue::model()->findAll($criteria);
        // 主要联系人，随行人员
        foreach ($models as $val) {
            $return['list'][] = array(
                'value' => $val->id,
                'name' => $val->name,
                'is_main' => $val->is_main,
                'view_link' => Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/view', array('id'=> $val->id))
            );
        }

        $this->send($return);
    }

    /**
     * 创建随行人员
     * @param int $type 0普通随行人员，1查主要联系人
     */
    public function actionCreate($type=0)
    {
        $model = new Retinue;

        $model->scenario = 'create';

        if(isset($_POST['Retinue']))
        {
            $model->attributes = $_POST['Retinue'];
            $model->user_id = Yii::app()->api->id;
            $model->is_main = intval($type);
            if ($model->validate()) {
                if($model->save(false) && $this->log('创建随行人员',ManageLog::user,ManageLog::create))
                    $this->send();
                else
                    $this->send_error_form($this->form_error($model));
            } else {
                $this->send_error_form($this->form_error($model));
            }

        }

        $this->send_csrf();
    }

    /**
     * 更新随行人员
     * @param $id
     */
    public function actionUpdate($id)
    {
        if(isset($_POST['Retinue']))
        {
            $model = $this->loadModel($id,array(
                'condition' => '`user_id`=:user_id AND `is_main`=0 AND `status`=1',
                'params' => array(':user_id'=>Yii::app()->api->id)
            ));

            $model->scenario = 'update';
            $model->attributes=$_POST['Retinue'];
            if ($model->validate()) {
                if($model->save(false) && $this->log('更新随行人员',ManageLog::user,ManageLog::update))
                    $this->send();
                else
                    $this->send_error_form($this->form_error($model));
            } else {
                $this->send_error_form($this->form_error($model));
            }

        }
        $this->send_csrf();
    }

    /**
     * 更新主要联系人
     * @param $id
     */
    public function actionUpdate_main($id)
    {
        if(isset($_POST['Retinue']))
        {
            $model = $this->loadModel($id,array(
                'condition' => '`user_id`=:user_id AND `is_main`=1 AND `status`=1',
                'params' => array(':user_id'=>Yii::app()->api->id)
            ));
            $model->scenario = 'update_main';
            $model->attributes=$_POST['Retinue'];      
            if ($model->validate()) 
            {
                if($model->save(false) && $this->log('更新随行人员（主要联系人）',ManageLog::user,ManageLog::update))
                    $this->send();
                else
                    $this->send_error_form($this->form_error($model));
            } else 
                $this->send_error_form($this->form_error($model));
        }
        $this->send_csrf();
    }

    /**
     * 删除随行人员
     * @param $id
     */
    public function actionDelete($id)
    {
        $result = $this->loadModel($id,
            '`user_id`=:user_id AND `is_main`=0 AND `status`=1',
            array(
                ':user_id'=>Yii::app()->api->id,
            )
        )->updateByPk($id,array('status'=>-1));
        
        if($result)
            $this->log('删除随行人员',ManageLog::user,ManageLog::delete);

        $this->send();
    }

}