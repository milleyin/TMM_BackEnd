<?php
/**
 * 展示屏
 * @author Changhai Zhan
 * yiic.php pad
 */
class PadCommand  extends ConsoleCommand
{
    /**
     * pad index
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('state!=:state');
        $criteria->params[':state'] = Pad::PAD_STATE_ABNORMAL;
        if ( !!$count= Pad::model()->updateAll(array('state'=>Pad::PAD_STATE_ABNORMAL, 'up_time'=>time()), $criteria))
            $this->logText[] = $count;
        else
            $this->logText[] = 0;
        return self::right;
    }
}