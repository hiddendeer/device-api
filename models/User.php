<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_name', 'user_password', 'full_name','phone','create_time'], 'required'],
            [['sex','age'], 'integer'],
            [['avatar','address'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_name' => 'user_name',
            'full_name' => 'full_name',
            'user_password' => 'user_password',
            'phone' => 'phone',
            'sex' => 'sex',
            'age' => 'age',
            'avatar' => 'avatar',
            'address' => 'address',
            'create_time' => 'create_time'
        ];
    }
}