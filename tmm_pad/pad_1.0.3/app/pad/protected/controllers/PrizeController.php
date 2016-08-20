<?php
namespace app\controllers;

use FrontController;

/**
 * 奖品
 * @author Changhai Zhan
 *
 */
class PrizeController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Prize';
    
    /**
     * 首页 抽奖
     * 1 查询是否有抽奖机会 抽奖机会和当前配置类型相同 如果是付费抽奖 必须没有待选择的奖品 
     * 2 免费抽奖 抽中奖品减少库存 
     * 3 付费抽奖 抽中奖品不减少库存 抽奖记录 设置 待选择 (谢谢参与除外)
     */
    public function actionIndex()
    {
        $criteria = new \CDbCriteria;
        $criteria->addColumnCondition(array(
            'type' => \Config::Config_TYPE_PAY,                                                         //付费抽奖
            'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_SELECT,  //待选择
            'status' => \Record::_STATUS_NORMAL,                                                  //中奖记录
            'pad_id' => \Yii::app()->user->padId,
            'store_id' => \Yii::app()->user->id,
        ));
        $modelRecord = \Record::model()->find($criteria);
        //如果 找到记录 说明有没确认的记录
        if ($modelRecord) {
            $this->redirect(array('record/select'));
        }
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Chance_Config',
            'Chance_User',
        );
        //条件 次数大于零 当前抽奖类型和配置类型一样
        $criteria->addCondition('`t`.`number`>0 AND `Chance_Config`.`type`=`t`.`type`');
        $criteria->addColumnCondition(array(
            '`t`.`pad_id`'=>\Yii::app()->user->padId,
            '`t`.`store_id`'=>\Yii::app()->user->id,
            '`t`.`date_time`'=>strtotime(date('Y-m-d', time())),
            '`t`.`status`'=>\Chance::_STATUS_NORMAL,
            '`Chance_Config`.`pad_id`'=>\Yii::app()->user->padId,
            '`Chance_Config`.`store_id`'=>\Yii::app()->user->id,
        ));
        $this->_modelName = 'Chance';
        //是否有抽奖机会
        $model = $this->loadModel($criteria);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            if ( !$chanceModel = \Chance::model()->find($criteria)) {
                throw new \Exception('没有找到用户有抽奖机会');
            }
            //查找 展示屏的奖品
            $criteria = new \CDbCriteria;
            $criteria->with = array(
                'Prize_Upload',
            );
            $criteria->addColumnCondition(array(
                '`t`.`pad_id`'=>\Yii::app()->user->padId,
                '`t`.`store_id`'=>\Yii::app()->user->id,
            ));
            $criteria->order = '`t`.`position`';
            if ( !$models = \Prize::model()->findAll($criteria)) {
                throw new \Exception('没有找到奖品');
            }
            //抽奖概率
            $rateArr = array();
            //奖品Model
            $prizeModels = array();
            foreach ($models as $model) {
                //必中的
                if ( !isset($award) && $model->odds == -1 && $model->number != 0 && $model->status == \Prize::_STATUS_NORMAL) {
                    $award = $model->position;
                }
                $rateArr[$model->position] = $this->rate($model);
                $prizeModels[$model->position] = $model;
            }
            if (array_sum($rateArr) == 0 && !isset($award)) {
                throw new \Exception('程序异常');
            }
            //抽奖
            if ( !isset($award)) {
                $award = \Helper::dice($rateArr);
            }
            if ( !isset($rateArr[$award])) {
                throw new \Exception('程序异常');
            } else {
                $prizeModel = $prizeModels[$award];
            }
            //如果是免费 或者 谢谢参与 才减少库存
            if ($prizeModel->status == \Prize::_STATUS_DELETED || $chanceModel->Chance_Config->type == \Config::Config_TYPE_FREE) {
                // 如果奖品是有库存的 
                if ($prizeModel->number != -1) {
                    //减少库存
                    if ( !\Prize::model()->updateByPk($prizeModel->id, array('number' => new \CDbExpression('`number`-1')))) {
                        throw new \Exception('减少库存奖品错误');
                    }
                    $prizeModel = \Prize::model()->findByPk($prizeModel->id);
                    //再次验证
                    if ($prizeModel->number < 0) {
                        throw new \Exception('减少库存奖品错误');
                    }
                }
            }
            //减少抽奖机会
            if ( !\Chance::model()->updateByPk($chanceModel->id, array('number' => new \CDbExpression('`number`-1'), 'up_time'=>time()))) {
                throw new \Exception('减少抽奖机会错误');
            }
            $chanceModel = \Chance::model()->findByPk($chanceModel->id);
            //再次验证
            if ($chanceModel->number < 0) {
                throw new \Exception('减少抽奖机会错误');
            }
            //抽奖完成 没有机会了 更新已抽完
            if ($chanceModel->number == 0) {
                if ( !\Chance::model()->updateByPk($chanceModel->id, array('status' => \Chance::_STATUS_DELETED, 'up_time'=>time()))) {
                    throw new \Exception('更新抽奖机会错误');
                }
            }
            // 谢谢参与
            if ($prizeModel->status == \Prize::_STATUS_DELETED) {
                $recordModel = new \Record;
                $recordModel->scenario = 'create';
                $recordModel->attributes = array(
                    'user_id' => $chanceModel->user_id,
                    'store_id' => $chanceModel->store_id,
                    'pad_id' =>$chanceModel->pad_id,
                    'config_id' =>$chanceModel->config_id,
                    'type' => $chanceModel->Chance_Config->type,
                    'prize_id' => $prizeModel->id,
                    'chance_id' => $chanceModel->id,
                    'receive_type' => $prizeModel->receive_type,
                    'prize_name' => $prizeModel->name,
                    'prize_info' => $prizeModel->info,
                    'url' => $prizeModel->url,
                    // 无需兑换
                    'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_NONE,
                    //无需打印
                    'print_status' => \Record::RECORD_PRINT_STATUS_NONE,
                    //谢谢参与
                    'status' => \Record::_STATUS_DELETED,
                );
                if ( !$recordModel->save()) {
                    throw new \Exception('添加抽奖记录错误');
                }
                //免费的记录
            } elseif ($chanceModel->Chance_Config->type == \Config::Config_TYPE_FREE) {
                $recordModel = new \Record;
                $recordModel->scenario = 'create';
                $recordModel->attributes = array(
                    'user_id' => $chanceModel->user_id,
                    'store_id' => $chanceModel->store_id,
                    'pad_id' =>$chanceModel->pad_id,
                    'config_id' =>$chanceModel->config_id,
                    'type' => $chanceModel->Chance_Config->type,
                    'prize_id' => $prizeModel->id,
                    'chance_id' => $chanceModel->id,
                    'receive_type' => $prizeModel->receive_type,
                    'prize_name' => $prizeModel->name,
                    'prize_info' => $prizeModel->info,
                    'url' => $prizeModel->url,
                    'code' => $recordModel->getRandCode(),
                    //兑换状态 有赞、无需兑换 =》 无需兑换 其他设置 未兑换
                    'exchange_status' =>
                        ($prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_YZ ||
                        $prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_NONE) ?
                        \Record::RECORD_EXCHANGE_STATUS_NONE :
                        \Record::RECORD_EXCHANGE_STATUS_NO,
                    //无需打印 到店兑换 =》未打印 其他 无需打印
                    'print_status' =>$prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE ?
                    \Record::RECORD_PRINT_STATUS_NO : \Record::RECORD_PRINT_STATUS_NONE,
                    //中奖记录
                    'status' => \Record::_STATUS_NORMAL,
                );
                if ( !$recordModel->save()) {
                    throw new \Exception('添加抽奖记录错误');
                }
                $uploadModel = new \Upload;
                $attributes = $prizeModel->Prize_Upload->getAttributes();
                $attributes['type'] = \Upload::UPLOAD_TYPE_RECORD;
                $attributes['upload_id'] = $recordModel->id;
                unset($attributes['id']);
                $uploadModel->setAttributes($attributes, false);
                $uploadModel->path = $uploadModel->getPathName() . '.' . pathinfo($prizeModel->Prize_Upload->getAbsolutePath(), PATHINFO_EXTENSION);
                if ( !$uploadModel->save(false)) {
                    throw new \Exception('复制奖品图片记录错误');
                }
                if ( !copy($prizeModel->Prize_Upload->getAbsolutePath(), $uploadModel->getAbsolutePath())) {
                   // throw new \Exception('复制奖品图片错误');
                }
                //付费的
            } elseif ($chanceModel->Chance_Config->type == \Config::Config_TYPE_PAY) {
                $recordModel = new \Record;
                $recordModel->scenario = 'create';
                $recordModel->attributes = array(
                    'user_id' => $chanceModel->user_id,
                    'store_id' => $chanceModel->store_id,
                    'pad_id' =>$chanceModel->pad_id,
                    'config_id' =>$chanceModel->config_id,
                    'type' => $chanceModel->Chance_Config->type,
                    'prize_id' => $prizeModel->id,
                    'chance_id' => $chanceModel->id,
                    'receive_type' => $prizeModel->receive_type,
                    'prize_name' => $prizeModel->name,
                    'prize_info' => $prizeModel->info,
                    'url' => $prizeModel->url,
                    'code' => $recordModel->getRandCode(),
                    //兑换状态
                    'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_SELECT,
                    //无需打印 无需打印
                    'print_status' => \Record::RECORD_PRINT_STATUS_NONE,
                    //中奖记录
                    'status' => \Record::_STATUS_NORMAL,
                );
                if ( !$recordModel->save()) {
                    throw new \Exception('添加抽奖记录错误');
                }
                $uploadModel = new \Upload;
                $attributes = $prizeModel->Prize_Upload->getAttributes();
                $attributes['type'] = \Upload::UPLOAD_TYPE_RECORD;
                $attributes['upload_id'] = $recordModel->id;
                unset($attributes['id']);
                $uploadModel->setAttributes($attributes, false);
                $uploadModel->path = $uploadModel->getPathName() . '.' . pathinfo($prizeModel->Prize_Upload->getAbsolutePath(), PATHINFO_EXTENSION);
                if ( !$uploadModel->save(false)) {
                    throw new \Exception('复制奖品图片记录错误');
                }
                if ( !copy($prizeModel->Prize_Upload->getAbsolutePath(), $uploadModel->getAbsolutePath())) {
                   throw new \Exception('复制奖品图片错误');
                }
            }
            $return = true;
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::log($e->getMessage(), 'error', __METHOD__);
        }
        if (isset($return) && $return) {
            $this->render('index', array(
                'model'=>$prizeModel,
                'chanceModel'=>$chanceModel,
                'recordModel'=>$recordModel,
                'models' => $models,
                'site' => $award == 8 ? 0 : $award,
            ));
        } else {
            $this->returnMessage('程序异常');
        }
    }
    
    /**
     * 概率 如果禁用了 表示中奖概率为零 如果库存没有了 表示中奖概率为零
     * @param unknown $model
     * @return number
     */
    public function rate($model)
    {
        return ($model->odds == -1 || $model->status == \Prize::_STATUS_DISABLE || $model->number == 0) ? 0 : $model->odds;
    }
    
    /**
     * 奖品图片
     */
    public function actionImage()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Prize_Upload',
        );
        $criteria->addColumnCondition(array(
            '`t`.`pad_id`'=>\Yii::app()->user->padId,
            '`t`.`store_id`'=>\Yii::app()->user->id,
        ));
        $criteria->order = '`t`.`position`';
        
        $this->render('image', array(
            'models' => $this->loadModelAll($criteria)
        ));
    }
}