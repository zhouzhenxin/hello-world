<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/12
 * Time: 下午2:52
 */
class NiceDelete{
    public $id;

    public function rules()
    {
        return ['id','required','message'=>"参数不合法"];
    }

    public function deleteById()
    {
        if ($this->validate()){
            $model = Nice::find($this->id);
            $result = $model::delete();
            if ($result === false) {
                throw new BussinessException(ErrorHelper::SYS_ERROR);
            }

        }else{
            new BussinessException(ErrorHelper::PARAM_WRONG);
        }
    }
}