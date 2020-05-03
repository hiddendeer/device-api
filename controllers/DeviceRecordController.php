<?php


namespace app\controllers;

use app\helper\uuidHelper;
use Yii;
use app\helper\tokenHelper;
use yii\rest\ActiveController;
use app\models\DeviceRecord;

class DeviceRecordController extends ActiveController
{
    public $modelClass = 'models\DeviceRecord';

    public function beforeAction($action)
    {
        $code = tokenHelper::validateToken();
        if (isset($code) && $code == 401) {
            $data = ['code' => $code, 'message' => 'token验证失败'];
            Yii::$app->response->data = $data;
        }

        return parent::beforeAction($action);

    }

    public function actionCreateRecord()
    {
        $data = Yii::$app->request->post();
        $u_id = Yii::$app->request->get('id');

        $model = new DeviceRecord();
        $model->id = uuidHelper::uuid();
        $model->setAttributes($data);
        $model->u_id = $u_id;
        $model->status = 1;//入库状态
        $model->create_time = date('Y-m-d H:i:s', time());

        if ($model->save() && $model->validate()) {
            unset($model);
            //入库和出库同步
            $model = new DeviceRecord();
            $model->id = uuidHelper::uuid();
            $model->setAttributes($data);
            $model->u_id = $u_id;
            $model->status = 2;//入库状态
            $model->create_time = date('Y-m-d H:i:s', time());
            if ($model->save() && $model->validate()) {
                $data = [
                    'code' => 200,
                    'message' => 'OK',
                    'results' => $model->attributes
                ];
                return $data;
            }

            return ['code' => 1100, 'message' => '入库失败', 'errors' => $model->getErrors()];

        } else {
            return ['code' => 1100, 'message' => '出库失败', 'errors' => $model->getErrors()];
        }


    }

}