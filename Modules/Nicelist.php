<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/12
 * Time: 上午11:41
 */

class Nicelist extends BaseModel
{
    public $sex;
    public $phone;
    public $birthday;
    public $page;
    public $page_size;
    public function rules()
    {
        return [
            ['page','default','value'=>1],
            ['page_size','default','value'=>10],
            ['sex','default','value'=>null],
            ['phone','default','value'=>null],
            ['birthday','default','value'=>null]
        ];
    }

    public function getnicelist()
    {
        if ($this->validate()){
            $appid = 1;
            $model = new Nice();
            $map = ['appid'=>$appid,'phone'=>$this->phone,'sex'=>$this->sex,'birthday'=>$this->birthday];
            $offset = $this->page_size*($this->page-1);
            $count = $model::find()->filterWhere($map)->count();
            $result = $model::find()
                ->select("id,parents,phone,sex,FROM_UNIXTIME(birthday) as birthday,FROM_UNIXTIME(createtime) createtime")
                ->filterWhere($map)
                ->orderBy(['id'=>SORT_DESC])
                ->limit($this->page_size)
                ->offset($offset)
                ->asArray()
                ->all();
            if ($result ===false){
                throw new BussinessException(ErrorHelper::SYSERROR);
            }else{
                $ajaxReturn['total_page'] = $count;
                $ajaxReturn['list'] = $result;
                return $ajaxReturn;
            }
        }
    }



}