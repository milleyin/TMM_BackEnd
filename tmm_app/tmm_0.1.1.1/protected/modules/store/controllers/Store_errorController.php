<?php
/**
 * Store_errorController
 * @author Moore Mo
 * Class Store_errorController
 */
class Store_errorController extends StoreMainController
{

    public function actionIndex()
    {
        if (!!$error = Yii::app()->errorHandler->error) {
            if (Yii::app()->params['store_error_json'])
                $this->send_error_system($error['message']);
            else {
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->renderPartial('index', $error);
            }
        }

    }
}