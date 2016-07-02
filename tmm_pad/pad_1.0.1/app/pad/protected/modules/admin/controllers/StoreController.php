<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:28:33 */
class StoreController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Store';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Store('search');
        //清除默认值
        $model->unsetAttributes();
        if (isset($_GET['Store']))
            $model->attributes = $_GET['Store'];

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
            'Store_Role',
            'Store_Area_province'=>array('select'=>'name'),
            'Store_Area_city'=>array('select'=>'name'),
            'Store_Area_district'=>array('select'=>'name'),
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id),
        ));
    }
    
    /**
     * 二维码
     * @param unknown $id
     */
    public function actionQrcode($id)
    {
        \Yii::import ('ext.phpqrcode.phpqrcode', true);
        \QRcode::png($this->createAbsoluteUrl('/user/record/index', array('id'=>$id)), false, QR_ECLEVEL_L, 10, 2, true);
    }

    /**
     * 创建
     * @return Success page "view"
     */
    public function actionCreate()
    {
        $model = new \Store;
        $model->Store_Role = new \Role;
        $model->Store_Password = new \Password;
              
        $model->scenario = 'create';    
        $model->Store_Password->scenario = 'create';
        $model->Store_Role->scenario = 'create';
        
        $this->ajaxVerify(array($model, $model->Store_Password), 'store-form');

        if (isset($_POST['Store'], $_POST['Password']))
        {
            $model->attributes = $_POST['Store'];
            $model->Store_Password->attributes = $_POST['Password'];
            $model->Store_Role->type = \Role::ROLE_TYPE_STORE;
            $model->Store_Password->type = \Password::PASSWORD_TYPE_LOGIN;
            if ($model->validate() && $model->Store_Role->validate() && $model->Store_Password->validate())
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    if ($model->Store_Role->save(false))
                    {
                        $model->id = $model->Store_Role->id;
                        $model->Store_Password->role_id = $model->Store_Role->id;
                        if ( !$model->save(false))
                            throw new \Exception('保存体验店表错误');
                        if ( !$model->Store_Password->execute())
                            throw new \Exception('保存体验店密码表错误');
                    }
                    else
                        throw new \Exception('保存角色表错误');
                    $transaction->commit();
                }
                catch(\Exception $e)
                {
                    $transaction->rollBack();
                }
                $this->redirect(array('view', 'id'=>$model->id));
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
                'Store_Role',
                'Store_Password',
        );
        $criteria->addColumnCondition(array(
             '`Store_Role`.`status`'=>\Role::_STATUS_NORMAL
        ));
        $model = $this->loadModelByPk($id, $criteria);
        
        $model->scenario = 'create';
        $model->Store_Password->scenario = 'update';
        $model->Store_Role->scenario = 'update';
        
        $this->ajaxVerify($model, 'store-form');

        if (isset($_POST['Store'], $_POST['Password']))
        {
            $model->attributes = $_POST['Store'];
            $model->Store_Password->attributes = $_POST['Password'];
            if ($model->validate() && $model->Store_Role->validate() && $model->Store_Password->validate())
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    if ( !$model->save(false))
                        throw new \Exception('保存体验店表错误');
                    if ( !$model->Store_Password->execute())
                        throw new \Exception('保存体验店密码表错误');
                    $transaction->commit();
                }
                catch(\Exception $e)
                {
                    $transaction->rollBack();
                }
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Store::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Store::_STATUS_DELETED));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Store::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Store::_STATUS_DISABLE));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Store::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Store::_STATUS_NORMAL));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Store::_STATUS_DELETED))->updateByPk($id, array('status'=>\Store::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
