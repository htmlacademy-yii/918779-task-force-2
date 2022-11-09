<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Category;
use app\models\Task;
use app\models\Attachment;
use yii\web\UploadedFile;

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
            ['runtime', 'date', 'format' => 'php:Y-m-d'],
            ['runtime', 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>', 'type' => 'date'],
            [['estimate', 'category_id'], 'integer'],
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
        $task->category_id = $this->category_id;
        $task->estimate = $this->estimate;
        $task->runtime = $this->runtime;
        $task->status = Task::STATUS_NEW;

        return $task;

    }

    public function uploadAttachment()
    {

        $this->attachment_title = $this->uploadFile(UploadedFile::getInstances($this, 'attachment'));

        if (count($this->attachment_title) > 0 && $addTask()->id) {
            foreach ($this->attachment_title as $name) {
                $file = new Attachment();
                $file->task_id = $addTask()->id;
                $file->attachment_title = $name;

                return $file;
            }
        }
    }
}
