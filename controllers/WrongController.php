<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use app\models\UserWrong;

class WrongController extends \yii\web\Controller
{
    //错题集
    public function actionInfo()
    {
        //用户id
        $headers = Yii::$app->request->headers;
        $userId = intval($headers->get('user-id'));
        if(empty($userId)){
            $data = [
                'errCode'=>100,
                'Message'=>'请先登录'
            ];
            return Json::encode($data);
        }
        $res = UserWrong::find()->where(['user_id' => $userId, 'status' => 1])->asArray()->all();
        $data = [
            'errCode'=>200,
            'Message'=>'success',
            'data'=>$res
        ];
        return Json::encode($data);
    }
}
