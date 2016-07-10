<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:26:15 */
class PrizeController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Prize';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Prize('search');        
        $model->Prize_Store = new \Store('search');
        $model->Prize_Pad = new \Pad('serch');
        $model->Prize_Upload = new \Upload('search');        
        //清除默认值
        $model->unsetAttributes();
        $model->Prize_Store->unsetAttributes();
        $model->Prize_Pad->unsetAttributes();
        $model->Prize_Upload->unsetAttributes();
        
        if (isset($_GET['Prize']))
            $model->attributes = $_GET['Prize'];
        if (isset($_GET['Store']))
            $model->Prize_Store->attributes = $_GET['Store'];
        if (isset($_GET['Pad']))
            $model->Prize_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Upload']))
            $model->Prize_Upload->attributes = $_GET['Upload'];

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
            'Prize_Store',
            'Prize_Pad',
            'Prize_Upload'
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 更新
     * @param integer $id
     * @return Success page "view"
     */
    public function actionUpdate($id)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = array(
            'Prize_Upload',
            'Prize_Store',
            'Prize_Pad',
        );
        $criteria->addColumnCondition(array(
            '`Prize_Pad`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Prize_Store`.`status`'=>\Store::_STATUS_NORMAL
        ));
        $model = $this->loadModelByPk($id, $criteria);
        $model->odds = $model->getOddsPercent();
        if ( !$model->Prize_Upload) {
            $model->Prize_Upload = new \Upload;
            $model->Prize_Upload->type = \Upload::UPLOAD_TYPE_PRIZE;
            $model->Prize_Upload->scenario = 'create_image';
        } else {
            $model->Prize_Upload->scenario = 'update_image';
        }
        if (isset($_POST['Prize']['receive_type']) && $_POST['Prize']['receive_type'] == \Prize::PRIZE_RECEIVE_TYPE_YZ)
           $model->scenario = 'update_yz';
        else
          $model->scenario = 'update';

        $this->ajaxVerify($model, 'prize-form');
        
        if (isset($_POST['Prize']))
        {
            $old_count = $model->count;
            $model->attributes = $_POST['Prize'];
            if ($model->validate() && $model->Prize_Upload->validate()) {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    // 谢谢参与 修改后 变成 正常的奖品
                    if ($model->status == \Prize::_STATUS_DELETED)
                        $model->status = \Prize::_STATUS_NORMAL;
                    if ($old_count != $model->count)
                        $model->number = $model->count;
                    $model->odds = $model->setOddsInteger();
                    if (! $model->save(false)) {
                        throw new \Exception('更新奖品失败');
                    }
                    if ($model->Prize_Upload->isNewRecord) {
                        $model->Prize_Upload->upload_id = $model->id;
                    }
                    if (! $model->Prize_Upload->save(false)) {
                        throw new \Exception('保存奖品图片失败');
                    }
                    $return = true;
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
                if (isset($return) && $return) {
                    $this->redirect($this->getLastUrl());
                }
            }
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 编辑谢谢
     * @param unknown $id
     * @throws \Exception
     */
    public function actionThanks($id)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = array(
            'Prize_Store',
            'Prize_Pad',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Prize::_STATUS_DELETED,
            '`Prize_Pad`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Prize_Store`.`status`'=>\Store::_STATUS_NORMAL
        ));
        $model = $this->loadModelByPk($id, $criteria);
        $model->odds = $model->getOddsPercent();
        
        $model->scenario = 'thanks';
        $this->ajaxVerify($model, 'prize-form');
        
        if (isset($_POST['Prize']))
        {
            $old_count = $model->count;
            $model->attributes = $_POST['Prize'];
            if ($old_count != $model->count) {
                $model->number = $model->count;
            }
            if ($model->validate()) {
                $model->odds = $model->setOddsInteger();
                if ($model->save(false)) {
                    $this->redirect($this->getLastUrl());
                }
            }
        }
        
        $this->render('thanks', array(
            'model'=>$model,
        ));
    }

    /**
     * 重置为谢谢参与
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDelete($id)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = array(
            'Prize_Upload',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Prize::_STATUS_DISABLE,
        ));
        $model = $this->loadModelByPk($id, $criteria);
        $model->scenario = 'reset';
        if ($model->validate()) {
            $transaction = $model->dbConnection->beginTransaction();
            try {
                // 谢谢参与
                if (! $model->save(false)) {
                    throw new \Exception('重置奖品失败');
                }
                if ($model->Prize_Upload) {
                    if ( !$model->Prize_Upload->delete()) {
                        throw new \Exception('保存奖品删除图片失败');
                    }
                }
                $return = true;
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 禁用
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDisable($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Prize::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Prize::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 激活
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionStart($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Prize::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Prize::_STATUS_NORMAL));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
