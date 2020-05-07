<?php

namespace app\controllers;

use app\helper\uuidHelper;
use Firebase\JWT\JWT;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Controller;
use app\models\User;
use Yii;


class SignUpController extends ActiveController
{
    public $modelClass = 'models\User';


    /**
     * 注册接口
     * auther chenshuaiyuan
     */
    public function actionRegister()
    {
        $request = \Yii::$app->request->post();

        //对用户名和密码规范验证
        if (!preg_match('/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u', $request['username'])) {
            $data = [
                'code' => 3,
                'message' => '用户名不规范',
                'data' => []
            ];
            return $data;
        }
        if (!preg_match('/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u', $request['password'])) {
            $data = [
                'code' => 3,
                'message' => '密码不规范',
                'data' => []
            ];
            return $data;
        }

        if (!preg_match('/^1[34578]\d{9}$/', $request['phone'])) {
            $data = [
                'code' => 3,
                'message' => '手机号不规范',
                'data' => []
            ];
            return $data;
        }

        if (strlen($request['username']) > 10 || strlen($request['username']) < 3) {
            $data = [
                'code' => 3,
                'message' => '用户名长度需要在3-10位之间',
                'data' => []
            ];
            return $data;
        }

        if (strlen($request['password']) > 40 || strlen($request['password']) < 3) {
            $data = [
                'code' => 3,
                'message' => '密码长度不能低于6位',
                'data' => []
            ];
            return $data;
        }

        //判断用户名是否重复
        $user_info = User::find()
            ->where(['user_name' => $request['username']])
            ->asArray()
            ->one();
        if (!empty($user_info)) {
            $data = [
                'code' => 3,
                'message' => '用户名已存在',
                'data' => []
            ];
            return $data;
        }

        //用户添加
        $insert_data = new User;
        $insert_data->id = uuidHelper::uuid();
        $insert_data->setAttributes($request);
        $insert_data->user_name = $request['username'];
        $insert_data->user_password = password_hash($request['password'], PASSWORD_DEFAULT);
        $insert_data->status = 1;
        $insert_data->create_time = date('Y-m-d H:i:s', time());

        if ($insert_data->save() && $insert_data->validate()) {
            $data = [
                'code' => 200,
                'message' => '注册成功',
            ];
            return $data;
        } else {
            return ['code' => 1100, 'message' => '注册失败', 'errors' => $insert_data->getErrors()];
        }
    }

}