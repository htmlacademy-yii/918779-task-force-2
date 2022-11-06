<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
use app\models\Category;
use app\models\Task;
use app\models\Attachment;
use yii;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

class AddTaskForm extends Model {

    public $title;
    public $description;
    public $category_id;
    public $location;
    public $estimate;
    public $runtime;
    public $attachment;

    public $attachment_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['estimate', 'category_id'], 'integer'],
            [['runtime'], 'date', 'format' => 'Y-m-d'],
            [['runtime'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>', 'type' => 'date'],
            [['attachment'], 'file', 'skipOnEmpty' => true, 'extensions' => null, 'maxFiles' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'estimate' => 'Бюджет',
            'location' => 'Локация',
            'runtime' => 'Срок исполнения',
            'attachment' => 'Добавить новый файл',
        ];
    }

    private function uploadFile($attachment)
    {
        $savedFiles = [];
        if (is_array($attachment) && count($attachment) && $this->validate()) {
            foreach ($attachment as $file) {
                $name = uniqid('upload') . '.' . $file->getExtension();
                if ($file->saveAs('@webroot/uploads/' . $name)) {
                    $savedFiles[] = $name;
                }
            }
        }
        return $savedFiles;
    }

    public function addTask()
    {

        $task = new Task();
        $task->user_id = Yii::$app->user->getId();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->estimate = $this->estimate;
        $task->runtime = $this->runtime;
        $task->status = Task::STATUS_NEW;

        if (!$task->save()) {
            throw new NotFoundHttpException('Не удалось загрузить обьявление');
        }

        $this->attachment_title = $this->uploadFile(UploadedFile::getInstances($this, 'attachment'));

        if (count($this->attachment_title) > 0 && $task->id) {
            foreach ($this->attachment_title as $name) {
                $file = new Attachment();
                $file->task_id = $task->id;
                $file->attachment_title = $name;
                $file->save();
            }
        }
    }
}
