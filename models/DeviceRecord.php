<?php


namespace app\models;

use yii\db\ActiveRecord;

class DeviceRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'u_id', 'device_no', 'pro_name', 'status','create_time'], 'required'],
            [['device_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'u_id' => 'u_id',
            'full_name' => 'full_name',
            'device_no' => 'device_no',
            'pro_name' => 'pro_name',
            'status' => 'status',
            'device_name' => 'device_name',
            'create_time' => 'create_time',
        ];
    }

}