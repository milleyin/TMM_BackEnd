<?php
namespace app\user\controllers;

use UserModulesController;

/**
 * Class ExpressController
 * @package app\user\controllers
 *
 * @author Moore Mo
 */
class ExpressController extends UserModulesController
{
    /**
     * 查看中奖记录
     * @param int $id 中奖记录id
     */
    public function actionView($id) {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Record_Upload'
        );
        $criteria->addColumnCondition(array(
            '`t`.`user_id`' => \Yii::app()->user->id,
            '`t`.`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_EXPRESS,
            '`t`.`status`' => \Record::_STATUS_NORMAL
        ));
        $criteria->addCondition('`t`.`exchange_status`=:no OR `t`.`exchange_status`=:yes');
        $criteria->params[':no'] = \Record::RECORD_EXCHANGE_STATUS_NO;
        $criteria->params[':yes'] = \Record::RECORD_EXCHANGE_STATUS_ING;
        
        $this->_modelName = 'Record';
        $model = $this->loadModelByPk($id, $criteria);

        $this->render('view', array(
            'model' => $model,
        ));
    }


    /**
     * 填写领奖信息
     * @param int $id 中奖记录id
     */
    public function actionCreate($id)
    {
        $model = new \Express;
        $model->scenario = 'create';
        
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            '`user_id`' => \Yii::app()->user->id,
            '`receive_type`' => \Prize::PRIZE_RECEIVE_TYPE_EXPRESS,
            '`exchange_status`' => \Record::RECORD_EXCHANGE_STATUS_NO,
            '`status`' => \Record::_STATUS_NORMAL
        ));
        $this->_modelName = 'Record';
        $model->Express_Record = $this->loadModelByPk($id, $criteria);
        
        if (isset($_POST['Express']))
        {
            $back = new \stdClass();
            $model->attributes = $_POST['Express'];
            if ($model->validate()) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    $model->user_id = \Yii::app()->user->id;
                    $model->record_id = $model->Express_Record->id;
                    if ( ! $model->save(false)) {
                        throw new \Exception('提交失败');
                    }
                    // 更新中奖记录的状态为 兑换中
                    if ( ! \Record::model()->updateByPk($model->Express_Record->id, array('exchange_status' => \Record::RECORD_EXCHANGE_STATUS_ING))) {
                        throw new \Exception('提交失败');
                    }
                    $back->status = 1;
                    $back->prompt = '提交成功';
                    $back->data = array('url' => $this->createAbsoluteUrl('express/success'));
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollback();
                    $back->status = 0;
                    $back->prompt = $e->getMessage();
                }
            } else {
                $back->status = 0;
                $back->prompt = $this->ajaxReturnError($model->getErrors());
            }
            // 返回处理结果
            $this->ajaxReturn($back);
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 领取奖品成功
     */
    public function actionSuccess() {
        $this->render('success');
    }

    /**
     * 获取地区
     * @param int $pid
     */
    public function actionGetArea($pid = 0){
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            'pid' => $pid,
            'status'=>\Area::_STATUS_NORMAL,
        ));
        $html = '';
        if( !!$areaModel = \Area::model()->findAll($criteria)) {
            foreach($areaModel as $model) {
                $html .= "<option value='" . $model->id . "'>" . \CHtml::encode($model->name) . "</option>";
            }
        }
        echo $html;
    }
}