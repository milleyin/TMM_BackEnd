<?php
namespace app\user\controllers;

use UserModulesController;

/**
 * Class IndexController
 * @package app\user\controllers
 *
 * @author Moore Mo
 */
class IndexController extends UserModulesController
{
    /**
     * 
     * 用户扫描二维码获取抽奖机会
     */
    public function actionIndex($id)
    {        
        $model = new \Chance;
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Pad_Store',
            'Pad_Config',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
            '`Pad_Config`.`status`'=>\Config::_STATUS_NORMAL,
        ));
        $this->_modelName = 'Pad';
        $model->Chance_Pad = $this->loadModelByPk($id, $criteria);
        
        $model->scenario = 'create';
        $model->attributes = array(
            'pad_id'=>$model->Chance_Pad->id,
            'config_id'=>$model->Chance_Pad->Pad_Config->id,
            'store_id'=>$model->Chance_Pad->Pad_Store->id,
            'user_id'=>\Yii::app()->user->id,
            'count'=>$model->Chance_Pad->Pad_Config->number,
            'number'=>$model->Chance_Pad->Pad_Config->number,
        );
        if ($model->validate())
        {
            $transaction = $model->dbConnection->beginTransaction();
            try
            {
                $model->status = \Chance::_STATUS_NORMAL;
                if ( !$model->save(false)) {
                    throw new \Exception('创建抽奖机会失败');
                }
                //将抽奖中的用户 更新为 未抽完
                $criteria = new \CDbCriteria;
                $criteria->addColumnCondition(array(
                    'pad_id'=>$model->pad_id,
                    'status'=>\Chance::_STATUS_NORMAL,
                ));
                //排除刚创建的
                $criteria->compare('id', '<>' . $model->id);
                if ( \Chance::model()->find($criteria))
                {
                   if ( !\Chance::model()->updateAll(array('status'=>\Chance::_STATUS_DISABLE, 'up_time'=>time()), $criteria)) {
                       throw new \Exception('更新抽奖机会失败');
                   }
                }
                $return = true;
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
        else
        {
            $criteria = new \CDbCriteria;
            $criteria->addColumnCondition(array(
                'pad_id'=>$model->Chance_Pad->id,
                'store_id'=>$model->Chance_Pad->Pad_Store->id,
                'user_id'=>\Yii::app()->user->id,
                'date_time'=>strtotime(date('Y-m-d', time()))
            ));
            $this->_modelName = 'Chance';
            $model = $this->loadModel($criteria);
            //如果没有抽奖完成
            if ($model->status == \Chance::_STATUS_DISABLE && $model->number > 0) 
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    $model->status = \Chance::_STATUS_NORMAL;
                    if ( !$model->save(false)) {
                        throw new \Exception('创建抽奖机会失败');
                    }
                    //将抽奖中的用户 更新为 未抽完
                    $criteria = new \CDbCriteria;
                    $criteria->addColumnCondition(array(
                            'pad_id'=>$model->pad_id,
                            'status'=>\Chance::_STATUS_NORMAL,
                    ));
                    //排除刚查询到的
                    $criteria->compare('id', '<>' . $model->id);
                    if ( \Chance::model()->find($criteria)) {
                        if ( !\Chance::model()->updateAll(array('status'=>\Chance::_STATUS_DISABLE, 'up_time'=>time()), $criteria)) {
                            throw new \Exception('更新抽奖机会失败');
                        }
                    }
                    $return = true;
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            } else {
                $return = true;
            }
        }
        if (isset($return) && $return) {
            $this->render('index', array('model' => $model));
        }
        else {
            throw new \CHttpException(404, '程序异常', 0);
        }
    }
}