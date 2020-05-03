<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\Topic;
use Yii;
use yii\helpers\Json;
use app\models\Collection;
use app\models\Score;
use yii\rest\ActiveController;

class TopicController extends ActiveController
{
    public $modelClass = 'common\models\User';
    protected function fetch($template, $vars = [], $config = [])
    {
        Yii::$app->response->send();
        $this->view->fetch($template, $vars, $config);
    }

    //获取题库
    public function actionInfo()
    {
//        $headers = Yii::$app->request->headers;
//        $request = Yii::$app->request->post();
        $info = Topic::find()->where(['type' => 1])->asArray()->all();
        print_r($info);exit;
        return Json::encode($info);
    }

    //提交题库
    public function actionSubmit()
    {
        $request = Yii::$app->request->post('list');
        //用户id
        $headers = Yii::$app->request->headers;
        $userId = intval($headers->get('user-id'));
        $examInfo = Json::decode($request);
        $info = [];
        foreach ($examInfo as $k => $v) {
            if ($v['choose'] != $v['correct']) {
                $info[$k]['user_id'] = $userId;
                $info[$k]['type'] = intval($v['type']);
                $info[$k]['topic'] = $v['topic'];
                $info[$k]['correct'] = $v[$v['correct']];
                $info[$k]['create_time'] = time();
              
            }
        }
        $allScore = count($examInfo);
        $scores = count($info);
        $diffScore = $allScore - $scores;
        $score['user_id'] = $userId;
        $score['all_score'] = $allScore;
        $score['score'] = $scores;
        $score['ceshi'] = 1;
        $score['code_type'] = 1;

        if (!empty($info)) {
            $this->actionAdd($info);
            $this->actionScore($score);
        }

        $data = [
            'errCode' => 1,
            'message' => 'success',
            'data' => [
                'allscore' => $allScore,
                'score' => $scores,
                'diffscore' => $diffScore
            ]
        ];
        return Json::encode($data);
    }

    //错题批量入库
    public function actionAdd($array)
    {
        $addInfo = array_values($array);
        $add = Yii::$app->db->createCommand()->batchInsert('exam_user_wrong', ['user_id', 'type', 'topic', 'correct', 'create_time'], $addInfo)->execute();
        return $add;
    }

    //收藏题目
    public function actionCollect()
    {
        $collect = Yii::$app->request->post();
        $collect_model = new Collection();
        $collect_model->user_id = $collect['user_id'];
        $collect_model->topic_id = $collect['topic_id'];
        $collect_model->create_time = time();
        $res = $collect_model->save();
        if(!empty($res)){
            $data = [
                'errCode'=>200,
                'message'=> '收藏成功'
            ];
            return Json::encode($data);
        }

     }

     //分数入库
     public function actionScore($score){
        // $score = Yii::$app->request->post();
        $score_model = new Score();
        $score_model->user_id = $score['user_id'];
        $score_model->all_score = $score['all_score'];
        $score_model->code_type = $score['code_type'];
        $score_model->score = $score['score'];
        $score_model->exam_type = $score['ceshi'];
        $value = 100;
        $score_model->score_ratio = round($score['score']/$score['all_score'],2)*$value.'%';
        $score_model->create_time  = date('Y-m-d H:i');
        $res = $score_model->save();
        if(!empty($res)){
            $data = [
                'errCode'=>200,
                'message'=> '分数入库'
            ];
            return Json::encode($data);
        }

     }
}
