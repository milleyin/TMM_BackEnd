<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:28:20 */
class ShopController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Shop';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Shop('search');
        $model->Shop_Store = new \Store('search');
        $model->Shop_Pad = new \Pad('search');
        $model->Shop_Upload = new \Upload('search');
        //清除默认值
        $model->unsetAttributes();
        $model->Shop_Store->unsetAttributes();
        $model->Shop_Pad->unsetAttributes();
        $model->Shop_Upload->unsetAttributes();
        if (isset($_GET['Shop']))
            $model->attributes = $_GET['Shop'];
        if (isset($_GET['Store']))
            $model->Shop_Store->attributes = $_GET['Store'];
        if (isset($_GET['Pad']))
            $model->Shop_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Upload']))
            $model->Shop_Upload->attributes = $_GET['Upload'];

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
            'Shop_Upload',
            'Shop_Pad',
            'Shop_Store'=>array(
              'with'=>array(
                  'Store_Area_province',
                  'Store_Area_city',
                  'Store_Area_district',
               ),
           ),
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 创建
     * @return Success page "view"
     */
    public function actionCreate($id)
    {
        $model = new \Shop;
        
        $this->_modelName = 'Pad';
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Pad_Store',
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
        ));
        $model->Shop_Pad = $this->loadModelByPk($id, $criteria);
        
        $model->Shop_Upload = new \Upload;
        $model->Shop_Upload->type = \Upload::UPLOAD_TYPE_SHOP;

        $model->scenario = 'create';
        $model->Shop_Upload->scenario = 'create_image';
        
        $this->ajaxVerify($model, 'shop-form');
       
        if (isset($_POST['Shop']))
        {
            $model->attributes = $_POST['Shop'];
            $model->store_id = $model->Shop_Pad->store_id;
            $model->pad_id = $model->Shop_Pad->id;
            if ($model->validate() && $model->Shop_Upload->validate())
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    if ( !$model->save(false))
                        throw new \Exception('保存商品表错误'); 
                    $model->Shop_Upload->upload_id = $model->id;
                    if ( !$model->Shop_Upload->save(false))
                        throw new \Exception('保存资源表错误');
                    else
                        $return = true;
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
                'Shop_Upload',
                'Shop_Pad'=>array(
                    'with'=>array(
                        'Pad_Store'
                    )
               ),
        );
        $model = $this->loadModelByPk($id, $criteria);

        $model->scenario = 'update';
        $model->Shop_Upload->scenario = 'update_image';
        
        $this->ajaxVerify($model, 'shop-form');

        if (isset($_POST['Shop']))
        {
            $model->attributes = $_POST['Shop'];
            if ($model->validate() && $model->Shop_Upload->validate())
            {
                $transaction = $model->dbConnection->beginTransaction();
                try
                {
                    if ( !$model->save(false))
                        throw new \Exception('保存商品表错误');
                    if ( !$model->Shop_Upload->save(false))
                        throw new \Exception('保存资源表错误');
                    else
                        $return = true;
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
     * 更新商品排序
     */
    public function actionSort()
    {
        if (isset($_POST['namename']) && is_array($_POST['namename']))
        {
            foreach ($_POST['namename'] as $id => $value)
            {
                $this->errorThrow = false;
                if ( !!$model = $this->loadModelByPk($id))
                {
                    $model->scenario = 'sort';
                    $model->attributes = array('sort' => $value);
                    $model->save();
                }
            }
        }
        $this->redirect(\Yii::app()->request->getUrlReferrer());
    }

    /**
     * 删除
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDelete($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Shop::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Shop::_STATUS_DELETED));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Shop::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Shop::_STATUS_DISABLE));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Shop::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Shop::_STATUS_NORMAL));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Shop::_STATUS_DELETED))->updateByPk($id, array('status'=>\Shop::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
