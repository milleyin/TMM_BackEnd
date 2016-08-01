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
     */
    public function actionIndex()
    {
        $criteria = new \CDbCriteria;
        $criteria->addCondition('number>0');
        $criteria->addColumnCondition(array(
            'pad_id'=>\Yii::app()->user->padId,
            'store_id'=>\Yii::app()->user->id,
            'date_time'=>strtotime(date('Y-m-d', time())),
            'status'=>\Chance::_STATUS_NORMAL,
        ));
        $this->_modelName = 'Chance';
        //是否有抽奖机会
        $model = $this->loadModel($criteria);
        
        $criteria_config = new \CDbCriteria;
        $criteria_config->with = array(
                'Config_Upload',
        );
        $criteria_config->addColumnCondition(array(
                '`t`.`pad_id`'=>\Yii::app()->user->padId,
                '`t`.`store_id`'=>\Yii::app()->user->id,
        ));
        $this->_modelName = 'Config';
        $model->Chance_Config = $this->loadModel($criteria_config);
        //开启事物
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            if ( !$chanceModel = \Chance::model()->find($criteria)) {
                throw new \Exception('没有找到用户有抽奖机会');
            }
            $chanceModel->Chance_Config = \Config::model()->find($criteria_config);
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
            // 如果奖品是有库存的 
            if ($prizeModel->number != -1) {
                //减少库存
                if ( !\Prize::model()->updateByPk($prizeModel->id, array('number' => new \CDbExpression('number-1')))) {
                    throw new \Exception('减少库存奖品错误');
                }
                $prizeModel = \Prize::model()->findByPk($prizeModel->id);
                //再次验证
                if ($prizeModel->number < 0) {
                    throw new \Exception('减少库存奖品错误');
                }
            }
            //减少抽奖机会
            if ( !\Chance::model()->updateByPk($chanceModel->id, array('number' => new \CDbExpression('number-1'), 'up_time'=>time()))) {
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
                    'prize_id' => $prizeModel->id,
                    'chance_id' => $chanceModel->id,
                    'receive_type' => $prizeModel->receive_type,
                    'prize_name' => $prizeModel->name,
                    'prize_info' => $prizeModel->info,
                    'url' => $prizeModel->url,
                    //'code' => $recordModel->getRandCode(),
                    'exchange_status' => \Record::RECORD_EXCHANGE_STATUS_NONE,  // 无需兑换
                    'status' => \Record::_STATUS_DELETED,                                                  //谢谢参与
                );
                if ( !$recordModel->save()) {
                    throw new \Exception('添加抽奖记录错误');
                }
            } else {
                $recordModel = new \Record;
                $recordModel->scenario = $prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_YZ ? 'create_yz' : 'create';
                $recordModel->attributes = array(
                    'user_id' => $chanceModel->user_id,
                    'store_id' => $chanceModel->store_id,
                    'pad_id' =>$chanceModel->pad_id,
                    'config_id' =>$chanceModel->config_id,
                    'prize_id' => $prizeModel->id,
                    'chance_id' => $chanceModel->id,
                    'receive_type' => $prizeModel->receive_type,
                    'prize_name' => $prizeModel->name,
                    'prize_info' => $prizeModel->info,
                    'url' => $prizeModel->url,
                    'code' => $recordModel->getRandCode(),
                    'exchange_status' =>($prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_YZ  ||  $prizeModel->receive_type == \Prize::PRIZE_RECEIVE_TYPE_NONE) ? \Record::RECORD_EXCHANGE_STATUS_NONE : \Record::RECORD_EXCHANGE_STATUS_NO, // 兑换
                    'status' => \Record::_STATUS_NORMAL,                                                 //中奖记录
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
        }
        if (isset($return) && $return) {
            $angle = array(
                '1'=> 337.5 + (mt_rand(-16875, 16875) / 1000),
                '2'=> 292.5 + (mt_rand(-16875, 16875) / 1000),
                '3'=> 247.5 + (mt_rand(-16875, 16875) / 1000),
                '4'=> 202.5 + (mt_rand(-16875, 16875) / 1000),
                '5'=> 157.5 + (mt_rand(-16875, 16875) / 1000),
                '6'=> 112.5 + (mt_rand(-16875, 16875) / 1000),
                '7'=> 67.5 + (mt_rand(-16875, 16875) / 1000),
                '8'=> 22.5 + (mt_rand(-16875, 16875) / 1000),
            );
            $this->render('index', array(
                'model'=>$prizeModel,
                'chanceModel'=>$chanceModel,
                'recordModel'=>$recordModel,
                'angle' => 360 * mt_rand(10, 20) + $angle[$award]
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