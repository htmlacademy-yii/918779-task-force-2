<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Category;
use app\models\Task;
use app\models\Attachment;

class AddTaskForm extends Model {

    public $title;
    public $description;
    public $category_id;
    public $location;
    public $estimate;
    public $runtime;

        /**
     * @var UploadedFile
     */
    public $imageFiles;

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
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4],
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
            'imageFiles' => 'Добавить новый файл',
        ];
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
        $task->city_id = 1;

        return $task;
    }

    public function uploads()
    {
        $attach = new Attachment();

        if ($this->imageFiles && $this->validate()) {
            foreach ($this->imageFiles as $file) {
                $newname = uniqid('upload') . '.' . $file->getExtension();
                $file->saveAs('@webroot/uploads/' . $newname);
                $attach->path .= $newname;
                $attach->title = $file->baseName;
            }
        }

        return $attach;
    }
}
