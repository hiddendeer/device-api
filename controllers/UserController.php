<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;

class UserController  extends Controller
{
    public function actionLogin()
    {
        $request = \Yii::$app->request->post();

        //查询数据库
        $res_info = User::find()
            ->where(['user_name' => $request['account'],'status'=>1])
            ->asArray()
            ->one();

        //验证账号密码正确
        if (empty($res_info)) {
            $data = [
                'errCode' => 301,
                'message' => '用户名不存在',
                'data' => []
            ];
        }
        if (!password_verify($request['password'], $res_info['user_password'])) {
            $data = [
                'errCode' => 302,
                'message' => '密码错误',
                'data' => []
            ];
        } else {
            //正确返回数据
            unset($res_info['user_password']);
            $data = [
                'errCode' => 200,
                'message' => '登录成功',
                'data' => $res_info
            ];
        }


        return \yii\helpers\Json::encode($data);
    }

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
                'errCode' => 3,
                'message' => '用户名不规范',
                'data' => []
            ];
            return \yii\helpers\Json::encode($data);
        }
        if (!preg_match('/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u', $request['password'])) {
            $data = [
                'errCode' => 3,
                'message' => '密码不规范',
                'data' => []
            ];
            return \yii\helpers\Json::encode($data);
        }
        //判断用户名是否重复
        $user_info = User::find()
            ->where(['user_name' => $request['username']])
            ->asArray()
            ->one();
        if (!empty($user_info)) {
            $data = [
                'errCode' => 3,
                'message' => '用户名已存在',
                'data' => []
            ];
            return \yii\helpers\Json::encode($data);
        }
        //用户添加
        $insert_data = new User;
        $insert_data->user_name = $request['username'];
        $insert_data->user_password = password_hash($request['password'], PASSWORD_DEFAULT);
        $insert_data->status = 1;
        $insert_data->create_time = time();
        $res = $insert_data->save();
        if (!empty($res)) {
            $data = [
                'errCode' => 200,
                'message' => '注册成功',
            ];
            return \yii\helpers\Json::encode($data);
        }
    }
}
