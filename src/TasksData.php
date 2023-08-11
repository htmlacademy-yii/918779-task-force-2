<?php

namespace  Taskforce;

use app\models\Task;
use app\models\Attachment;
use app\models\Response;
use app\models\Review;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii;

class TasksData
{
    public const DEFAULT_RESPONSES = 0;

    private $idCurrent;

    private function setIdCurrent()
    {

        $idCurrent = Yii::$app->user->getId();

        $this->idCurrent = $idCurrent;
    }

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get Task
     */

    public function getTask()
    {
        $task = Task::findOne($this->id);
        if (!$task) {
            throw new NotFoundHttpException("Задача с ID $this->id не найдена");
        }

        return $task;
    }

    /**
     * Get Attachments
     */
    public function getAttachments()
    {
        $attachments = Attachment::find()
        ->where(['task_id' => $this->id])
        ->all();

        return $attachments;
    }

    /**
     * Get Executor's ID
     */
    public function getIdExecutor()
    {
        $taskExecutor = Response::find($this->id)
        ->where(['user_id' => $this->idCurrent])
        ->andWhere(['position' => Response::POSITION_ACCEPTED])
        ->one();

        $idExecutor = $taskExecutor->user_id ?? null;

        return $idExecutor;
    }

    /**
     * Get Responses
     */
    public function getResponses()
    {
        $responses = new ActiveDataProvider([
            'query' => Response::find()
                ->where(['task_id' => $this->id])
                ->joinWith(['user', 'task'])
                ->andWhere(['OR', [
                'AND', ['task.user_id' => $this->idCurrent]], [
                'AND', ['response.user_id' => $this->idCurrent]]]),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $responses;
    }

    /**
     * Get Reviews count
     */
    public function getReviewsCount()
    {
        $reviews = new ActiveDataProvider([
            'query' => Review::find()
                ->where(['review.user_id' => $this->idCurrent])
                ->joinWith(['user', 'task']),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $reviewsCount = $reviews->getTotalCount();

        return $reviewsCount;
    }

    /**
     * Get Task Responses
     */
    public function getTaskResponses()
    {
        $taskResponses = Response::find($this->id)
        ->select('response.task_id')
        ->where(['response.task_id' => $this->id, 'response.user_id' => $this->idCurrent])
        ->joinwith(['task'])
        ->andWhere(['task.status' => Task::STATUS_NEW])
        ->count();

        return $taskResponses;
    }

    /**
     * Get Task Actions
     */
    public function getTaskActions()
    {
        $task = $this->getTask();
        $idExecutor = $this->getIdExecutor();
        $idCustomer = $task->user_id;
        $taskStatus = $task->status;
        $taskActions = new Tasks($taskStatus, $idCustomer, $idExecutor);

        return $taskActions;
    }
}
