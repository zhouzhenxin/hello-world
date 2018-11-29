<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/12
 * Time: 下午2:31
 */
Class NiceInfo extends BaseModel{

    public $id;

    public function rules()
    {
        return [['id'],'required','message'=>"参数不合法"];
    }

    public function getInfo()
    {
        if ($this->validate()){
            $model = new Nice();
            $map = ['appid'=>$this->appid,'id'=>$this->id];
            $result = $model::find()
                ->select("id,photos")
                ->filterWhere($map)
                ->asArray()
                ->one();
            if ($result === false){
                throw new BussinessException(ErrorHelper::SYS_ERROR);
            }else{
                $ajaxReturn = $result;
                return $ajaxReturn;
            }
        }else{
            throw new BussinessException(ErrorHelper::SYS_ERROR);
        }

    }
}