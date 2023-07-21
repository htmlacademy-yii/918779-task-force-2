<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Task;
use app\models\Review;
use Taskforce\Tasks;

class AddReviewForm extends Model {

    public $task_id;
    public $user_id;
    public $stats;
    public $comment;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['comment', 'stats'], 'required'],
            ['stats', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            ['stats', 'compare', 'compareValue' => 5, 'operator' => '<=', 'type' => 'number'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => 'Ваш комментарий',
            'stats' => 'Оценка работы',
        ];
    }

    /**
     * Add Review
     *
     */

    public function addReview($task_id)
    {
        $task = Task::findOne($task_id);
        $response = Response::findOne(['task_id' => $task_id, 'position' => 'accepted']);

        $review = new Review();
        $review->task_id = $task_id;
        $review->stats = $this->stats;
        $review->comment = $this->comment;
        $review->user_id = $response->user_id;
        $task->status = Tasks::STATUS_DONE;        

        $review->save();
        $task->save();
    }
}
