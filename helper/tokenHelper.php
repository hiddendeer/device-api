<?php


namespace app\helper;

use Yii;

class tokenHelper
{

    /**
     * @inheritdoc
     */
    public static function validateToken()
    {
        $token = Yii::$app->request->get();
        $redis = Yii::$app->redis;
        if (!empty($token['id']) && !empty($token['token'])) {
            $redis_token = $redis->get($token['id']);
            if ($redis_token != $token['token']) {
                return 401;
            }

        } else {
            return 401;
        }

    }


}