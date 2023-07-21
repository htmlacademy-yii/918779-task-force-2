<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Task;
use app\models\Response;

class AddResponseForm extends Model {

    public $task_id;
    public $user_id;
    public $price;
    public $comment;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'comment'], 'required'],
            [['task_id', 'user_id', 'price'], 'integer'],
            [['comment'], 'string'],
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
            'price' => 'Стоимость',
        ];
    }

    /**
     * Add Response
     *
     */
    public function addResponse($task_id)
    {
        $response = new Response();
        $response->user_id = Yii::$app->user->getId();
        $response->task_id = $task_id;
        $response->price = $this->price;
        $response->comment = $this->comment;
        $response->position = Response::POSITION_CONSIDERED;

        return $response->save();
    }

}
