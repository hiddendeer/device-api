<?php
namespace app\models;

use yii\db\ActiveRecord;

class Score extends ActiveRecord{
    public static function tableName()
    {
        return 'exam_user_score';
    }
}

