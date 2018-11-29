<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/12
 * Time: 下午3:29
 */

class NiceSave extends BaseModel
{
    public $id;
    public $sex;
    public $phone;
    public $birthday;

    public function rules()
    {
        return [
            ['id','default','value'=>null],
            ['sex','required','massage'=>"参数不合法"],
            ['phone','required','massage'=>"参数不合法"],
            ['birthday','required','massage'=>"参数不合法"],
        ];
    }

    public function save()
    {
        if ($this->validate()){
            if ($this->id){
                $model = Nice::findOne($this->id);
            }else{
                $model = new Nice();
                $model->appid=1;
                $model->createtime=time();
            }
            $model->sex = $this->sex;
            $model->phone = $this->birthday;
            $model->birthday = $this->birthday;

            $result = $model->save();

            if ($result === false){
                throw new BussinessException(ErrorHelper::SYS_ERROR);
            }else{
                $ajaxReturn = $result;
                return $ajaxReturn;
            }

        }else{
            throw new BussinessException();
        }
    }
}