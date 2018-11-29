<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/12
 * Time: 上午10:09
 */
class NiceController{

    public function actionNiceList()
    {
        $data['Nicelist'] = \Yii::$app->request->post();
        $model = new Nicelist();
        if ($model->load($data) && $result = $model->getnicelist()){
            self::returnJson(ErrorHelper::SUCCESS,'',$result);
        }
        self::returnJson();

    }

    public function actionInfo()
    {
        $data['NiceInfo'] = \Yii::$app->request->post();
        $model = new NiceInfo();
        if ($model->load($data)&&$result = $model->getInfo()){
            self::returnInfo(ErrorHelper::SUCCESS,'',$result);
        }
        self::returnInfo();
    }

    public function actionDelete()
    {
        $data['NiceDelete'] = \Yii::$app->request->post();
        $model = new NiceDelete();
        if ($model->load($data)&&$result = $model->deleteById()){
            self::returnInfo(ErrorHelper::SUCCESS,'',$result);
        }
        self::returnInfo();
    }

    public function actionSave()
    {
        $data['NiceSave'] = \Yii::$app->request->post();
        $model = new NiceSave();
        if ($model->load($data)&&$result = $model->save()){
            self::returnInfo(ErrorHelper::SUCCESS,'',$result);
        }
        self::returnInfo();
    }
}