<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\Json;
use app\models\Score;

class ScoreController extends Controller
{
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
        $score_info = Score::find()->where(['user_id'=>$userId])->asArray()->all();
        $practice = array();
        $simulation = array();
        if(empty($score_info)) {
            $data = [
                'errCode' => 300,
                'message' => 'fail',
            ];
            return Json::encode($data);
        }
        foreach ($score_info as  $v) {
            if ($v['exam_type'] == 1) {
                $practice[] = $v;
            } else {
                $simulation[] = $v;
            }
        }

        $data = [
            'errCode' => 200,
            'message' => 'success',
            'practice' => $practice,
            'simulation' => $simulation
        ];

        return Json::encode($data);
    }
}
