<?php

namespace app\controllers;

use app\helper\tokenHelper;
use app\models\DeviceRecord;
use app\models\User;
use yii\rest\ActiveController;
use Yii;

class InfoController extends ActiveController
{
    public $modelClass = 'models\User';

    public function beforeAction($action)
    {
        $code = tokenHelper::validateToken();
        if (isset($code) && $code == 401) {
            $data = ['code' => $code, 'message' => 'token验证失败'];
            Yii::$app->response->data = $data;
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionUserInfo($u_id)
    {
        //查询数据库
        $res_info = User::find()
            ->select('user_name,full_name')
            ->where(['id' => $u_id, 'status' => 1])
            ->asArray()
            ->one();

        if (empty($res_info)) {
            return ['code' => 402, 'message' => '未找到该用户信息', 'data' => []];
        }

        return ['code' => 200, 'message' => 'OK', 'data' => $res_info];
    }

    public function actionMyRecordIndex($u_id)
    {
        $res_info = DeviceRecord::find()
            ->where(['u_id' => $u_id, 'status' => 1, 'delete_flag' => 0])
            ->orderBy('create_time desc')
            ->asArray()
            ->all();

        return ['code' => 200, 'message' => 'OK', 'data' => $res_info];
    }
}