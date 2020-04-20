<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_conf_type".
 *
 * @property int $id 编号
 * @property string $name 数据字典类型名称
 * @property string $type 数据字典类型缩写
 * @property int $create_time 创建时间
 * @property int $add_user_id 添加人
 */
class ConfType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_conf_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_time', 'add_user_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'create_time' => 'Create Time',
            'add_user_id' => 'Add User ID',
        ];
    }
}
