<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:22:42 */
class AdController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Ad';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Ad('search');
        $model->Ad_Upload =  new \Upload('search');
        //清除默认值
        $model->unsetAttributes();
        $model->Ad_Upload->unsetAttributes();
        if (isset($_GET['Ad']))
            $model->attributes = $_GET['Ad'];
        if (isset($_GET['Upload']))
            $model->Ad_Upload->attributes = $_GET['Upload'];
        
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
        $criteria->with = array('Ad_Upload');
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 创建
     * @return Success page "view"
     */
    public function actionCreate()
    {
        $model = new \Ad;
        $model->Ad_Upload = new \Upload();
        $model->Ad_Upload->type = \Upload::UPLOAD_TYPE_AD;
        
        $model->scenario = 'create';
        $this->ajaxVerify($model, 'ad-form');

        if (isset($_POST['Ad']))
        {
            $model->attributes = $_POST['Ad'];
            if ($model->validate())
            {
                if ($model->type == $model::AD_TYPE_IMAGE) 
                    $model->Ad_Upload->scenario = 'create_image';
                else if ($model->type == $model::AD_TYPE_VIDEO)
                    $model->Ad_Upload->scenario = 'create_video';
                if ($model->Ad_Upload->validate())
                {
                    $transaction = $model->dbConnection->beginTransaction();
                    try
                    {
                        if ($model->save(false))
                        {
                            $model->Ad_Upload->upload_id = $model->id;
                            if ($model->Ad_Upload->save(false))
                                $return = true;
                            else 
                                throw new \Exception('保存资源表错误');
                        }
                        else
                            throw new \Exception('保存广告表错误');
                        $transaction->commit();
                    }
                    catch(\Exception $e)
                    {
                        $transaction->rollBack();
                    }
                    if (isset($return) && $return)
                        $this->redirect(array('view', 'id'=>$model->id));
                }
            }
        }
        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * 更新
     * @param integer $id
     * @return Success page "view"
     */
    public function actionUpdate($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Ad_Upload'
        );
        $model = $this->loadModelByPk($id, $criteria);
        
        if ($model->type == $model::AD_TYPE_IMAGE)
            $model->Ad_Upload->scenario = 'update_image';
        else if ($model->type == $model::AD_TYPE_VIDEO)
            $model->Ad_Upload->scenario = 'update_video';
        else 
            $model->Ad_Upload->scenario = 'update_image';
            
        $model->scenario = 'update';
        $this->ajaxVerify($model, 'ad-form');

        if (isset($_POST['Ad']))
        {
            $model->attributes = $_POST['Ad'];
            if ($model->validate() && $model->Ad_Upload->validate())
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    if ($model->save(false))
                    {
                        if ($model->Ad_Upload->save(false))
                            $return = true;
                        else
                            throw new \Exception('保存资源表错误');
                    }
                    else
                        throw new \Exception('保存广告表错误');
                    $transaction->commit();
                }
                catch(\Exception $e)
                {
                    $transaction->rollBack();
                }
                if (isset($return) && $return)
                      $this->redirect($this->getLastUrl());
            }
        }
        
        $this->render('update', array(
            'model'=>$model,
        ));
    }

    /**
     * 删除
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDelete($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Ad::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Ad::_STATUS_DELETED));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Ad::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Ad::_STATUS_DISABLE));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Ad::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Ad::_STATUS_NORMAL));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 还原
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionRestore($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Ad::_STATUS_DELETED))->updateByPk($id, array('status'=>\Ad::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
//     /**
//      * 清除记录
//      * @param integer $id the ID of the model to be deleted
//      * @return Success page "admin"
//      */
//     public function actionClear($id)
//     {
//         $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Ad::_STATUS_DELETED))->delete();
        
//         if ( !isset($_GET['ajax']))
//             $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//     }
}
