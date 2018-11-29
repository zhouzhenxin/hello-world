<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/10
 * Time: 上午11:21
 */
namespace PracticeManual;
use phpCreate\Zsgc;

class testController extends BaseController{

    public function actionList()
    {
        $date[] = \Yii::$app->request->post();
        $model = new Zsgc();
        if ($model->lode($date) && $result=$model->createFile()){
            self::returnJson(ErrorHelper::SUCCESS,'',$result);
        }

        self::returnJson();

    }


}