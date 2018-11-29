<?php

/**
 * This is the model class for table "{{%Nice}}".
 *
 * @property int $id 样片分类表
 * @property int $appid 数据库自增appid
 * @property string $name 分类名称
 * @property int $status 状态：1上架、0未上架
 * @property int $createtime 创建时间
 * @property int $ip 1：样片2：客片
 * @property int $flag 1：激活 2：未激活
 * @property int $sex 1：激活 2：未激活
 */
class Nice extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%nice}}';
    }

    public function rules()
    {
        return [
            [['appid', 'createtime'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 20],
            [['pass'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 1],
            [['flag'], 'string', 'max' => 1],
            [['ip'], 'string', 'max' => 11]
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appid' => 'Appid',
            'name' => 'Name',
            'pass' => 'Pass',
            'alias' => 'Alias',
            'createtime' => 'Createtime',
            'status' => 'Status',
            'flag' => 'Flag',
            'ip' => 'Ip',
        ];
    }
}