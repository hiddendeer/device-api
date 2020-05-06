<?php


namespace app\helper;

use Firebase\JWT\JWT;
use Yii;
use yii\helpers\Json;

class tokenHelper
{

    /**
     * @inheritdoc
     */
    public static function validateToken()
    {
        $token = Yii::$app->request->get();
        if (!isset($token['token']) || empty($token['token'])) return 401;

        $redis = Yii::$app->redis;

        try {
            $jwt_obj = JWT::decode($token['token'], '321', ['HS256']);
        } catch (\Exception $e) {
            return 401;
        }

        if (!empty($jwt_obj)) {
            $jwt_json = Json::encode($jwt_obj);
            $u_id = Json::decode($jwt_json, true)['data']['u_id'];
        }

        if (!empty($u_id) && !empty($token['token'])) {
            $redis_token = $redis->get($u_id);
            if ($redis_token != $token['token']) {
                return 401;
            }

        } else {
            return 401;
        }

    }


}