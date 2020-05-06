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
            return false;
        }

        return parent::beforeAction($action);
    }


    /**
     * @Notes: 列表
     * @param
     * @return array
     * @author: ChenShuaiYuan
     * @date: 2020-05-03 20:25
     */
    public function actionRecordIndex()
    {
        $res = DeviceRecord::find()->alias('m')
            ->select('m.id,m.u_id,m.device_no,m.pro_name,m.create_time,m.store_status,m.store_text')
            ->leftJoin('user u', 'u.id=m.u_id')
            ->where('m.delete_flag=0 and m.status=1')
            ->asArray()->all();

        return ['code' => 200, 'message' => 'OK', 'results' => $res];
    }

    public function actionRecordView()
    {
        $record_id = Yii::$app->request->get('key');

        if (empty($record_id)) {
            return ['code' => 11000, 'message' => '缺少参数', 'results' => []];
        }

        $res = DeviceRecord::find()->alias('m')
            ->select('m.id,m.u_id,m.device_no,m.pro_name,m.create_time,m.store_status,m.store_text,u.full_name')
            ->leftJoin('user u', 'u.id=m.u_id')
            ->where('m.delete_flag=0 and m.status=1')
            ->andWhere(['m.id' => $record_id])
            ->asArray()->one();

        return ['code' => 200, 'message' => 'OK', 'results' => $res];
    }


    /**
     * @Notes: 入库和出库（同步）
     * @param
     * @return array
     * @author: ChenShuaiYuan
     * @date: 2020-05-03 20:24
     */
    public function actionCreateRecord()
    {
        $data = Yii::$app->request->post();
        $u_id = Yii::$app->request->get('u_id');

        $model = new DeviceRecord();
        $model->id = uuidHelper::uuid();
        $model->setAttributes($data);
        $model->u_id = $u_id;
        $model->store_status = 1;
        $model->store_text = '出库';
        $model->status = 1;
        $model->delete_flag = 0;
        $model->create_time = date('Y-m-d H:i:s', time());

        if ($model->save() && $model->validate()) {
            unset($model);
            //入库和出库同步
            $model = new DeviceRecord();
            $model->id = uuidHelper::uuid();
            $model->setAttributes($data);
            $model->u_id = $u_id;
            $model->store_status = 2;
            $model->store_text = '入库';
            $model->status = 1;//入库状态
            $model->delete_flag = 0;
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

    public function actionRecordEdit()
    {

    }

} //Class End