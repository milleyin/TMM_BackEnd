<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:24:54 */
class ExpressController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Express';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Express('search');
        $model->Express_Record =  new \Record('search');
        //清除默认值
        $model->unsetAttributes();
        $model->Express_Record->unsetAttributes();
        if (isset($_GET['Express']))
            $model->attributes = $_GET['Express'];
        if (isset($_GET['Record']))
            $model->Express_Record->attributes = $_GET['Record'];

        $this->render('admin', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 查看
     * @param integer $id
     */
    public function actionView($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Express_Record'=>array(
                'with'=>array(
                    'Record_Pad',
                    'Record_Store',
                    'Record_Upload',
                 ),
            ),
            'Express_User' ,
            'Express_Area_province',
            'Express_Area_city',
            'Express_Area_district',
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }
    
    /**
     * 填写领奖信息
     * @param integer $id
     * @throws \Exception
     */
    public function actionCreate($id)
    {
        $model = new \Express;
        $model->scenario = 'create';
        $this->ajaxVerify($model, 'express-form');
        
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            '`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_EXPRESS,
            '`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_NO,
            '`status`' => \Record::_STATUS_NORMAL
        ));
        $this->_modelName = 'Record';
        $model->Express_Record = $this->loadModelByPk($id, $criteria);
       
        if (isset($_POST['Express']))
        {
            $model->attributes = $_POST['Express'];
            $model->user_id = $model->Express_Record->user_id;
            $model->record_id = $model->Express_Record->id;
            if ($model->validate()) 
            {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if ( !$model->save(false)) {
                        throw new \Exception('提交失败');
                    }
                    // 更新中奖记录的状态为 兑换中
                    if ( !\Record::model()->updateByPk($model->Express_Record->id, array('exchange_status' => \Record::RECORD_EXCHANGE_STATUS_ING))) {
                        throw new \Exception('提交失败');
                    }
                    $return = true;
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollback();
                }
                if (isset($return) && $return)
                    $this->redirect($this->getLastUrl());
            }
        }
        
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 发货
     * @param integer $id
     * @return Success page "view"
     */
    public function actionUpdate($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Express_Record'=>array(
                'with'=>array(
                    'Record_Pad',
                    'Record_Store',
                    'Record_Upload',
                ),
            ),
            'Express_User' ,
            'Express_Area_province',
            'Express_Area_city',
            'Express_Area_district',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Express::_STATUS_NORMAL,
            '`t`.`express_status`'=> \Express::EXPRESS_STATUS_NO,
        ));
        $model = $this->loadModelByPk($id, $criteria);

        $model->scenario = 'update';
        $this->ajaxVerify($model, 'express-form');
        if (isset($_POST['Express']))
        {
            $model->attributes = $_POST['Express'];
            $model->express_status = \Express::EXPRESS_STATUS_YES;
            $smsModel = new \Sms;
            $smsModel->scenario = 'notice';
            $smsModel->attributes = array(
                'use_type' =>\Sms::SMS_USE_TYPE_EXPRESS ,
                'role_id' => $model->user_id,
                'phone' => $model->phone,
                'text' => $this->getText($model),
            );
            if ($model->validate() && $smsModel->validate()) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if ( !$model->save(false)) {
                        throw new \Exception('提交失败');
                    }
                    // 更新中奖记录的状态为 兑换中
                    if ( !$smsModel->save(false)) {
                        throw new \Exception('短信发送失败');
                    }
                    if ( !\Record::model()->updateByPk($model->record_id, array('exchange_status'=>\Record::RECORD_EXCHANGE_STATUS_YES, 'exchange_time'=>time()))) {
                        throw new \Exception('兑换失败');
                    }
                    $return = true;
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollback();
                    $model->addError('express_name', reset(reset($smsModel->getErrors())));
                }
                if (isset($return) && $return)
                    $this->redirect($this->getLastUrl());
            }
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }
    
    
    public function getText($model)
    {
        return '您在' . \CHtml::encode($model->Express_Record->Record_Store->store_name) . '抽中的' . \CHtml::encode($model->Express_Record->prize_name) . '已发货，' . \CHtml::encode($model->express_name) . ' 快递单号：' . \CHtml::encode($model->express_code) . '，如有疑问请联系客服：400-019-7090。【田觅觅】';
    }
}
