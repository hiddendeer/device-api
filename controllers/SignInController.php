<?php

namespace app\controllers;


use Firebase\JWT\JWT;
use yii\rest\ActiveController;
use app\models\User;
use Yii;

class SignInController extends ActiveController
{
    public $modelClass = 'models\User';

    public function actionLogin()
    {

        $request = Yii::$app->request->post();
        $redis = Yii::$app->redis;

        //查询数据库
        $res_info = User::find()
            ->where(['user_name' => $request['username'], 'status' => 1])
            ->asArray()
            ->one();

        //验证账号密码正确
        if (empty($res_info)) {
            $data = [
                'code' => 301,
                'message' => '用户名不存在',
                'data' => []
            ];

            return $data;
        }
        if (!password_verify($request['password'], $res_info['user_password'])) {
            $data = [
                'code' => 302,
                'message' => '密码错误',
                'data' => []
            ];

            return $data;
        }

        //token
        $key = '321'; //key
        $time = time(); //当前时间
        $token = [
            'iss' => 'http://www.hiddendeer.cn', //签发者 可选
            'aud' => 'http://www.hiddendeer.cn', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + 7200, //过期时间,这里设置2个小时
            'data' => [ //自定义信息，不要定义敏感信息
                'u_id' => $res_info['id']
            ]
        ];

        $token = JWT::encode($token, $key);

        //token存储到redis,并且设置过期时间为两小时
        $redis->set($res_info['id'], $token);
        $redis->expire($res_info['id'], 10000);
        $expire_time = $redis->ttl($res_info['id']);

        if (empty($expire_time)) return ['errCode' => 302, 'message' => 'token验证失败', 'data' => []];
        $timestmp = strtotime("+$expire_time second", time());

        $res = [
            'id' => $res_info['id'],
            'username' => $res_info['user_name'],
            'full_name' => $res_info['full_name'],
            'phone' => $res_info['phone'],
            'sex' => $res_info['sex'],
            'age' => $res_info['age'],
            'avatar' => $res_info['avatar'],
            'address' => $res_info['address'],
            'expire' => $timestmp,
            'create_time' => $res_info['create_time'],
            'token' => $token
        ];

        unset($res_info['user_password']);
        //正确返回数据
        $data = [
            'code' => 200,
            'message' => '登录成功',
            'data' => $res
        ];


        return $data;
    }
}
