<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Category;
use app\models\Task;
use app\models\Attachment;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use Taskforce\Exceptions\NoAddTaskException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class AddTaskForm extends Model {

    public $title;
    public $description;
    public $category_id;
    public $location;
    public $estimate;
    public $runtime;
    public $lat;
    public $lng;
    public $city;

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
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 4],
            [['location'], 'string'],
            [['lat', 'lng'], 'double', 'max' => 13, 'min' => 10],
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
        if ($this->lat && $this->lng)
        {
            $task->lat = $this->lat;
            $task->lng = $this->lng;
            $task->location = $this->location;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$task->save()){
                throw new NoAddTaskException("Не удалось добавить задание");
            }

            $this->uploads($task);
            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $task;
    }

    public function uploads($newTask)
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');

        if ($this->imageFiles && $this->validate()) {
            foreach ($this->imageFiles as $file) {
                $attach = new Attachment();
                $newname = uniqid('upload') . '.' . $file->getExtension();
                $file->saveAs('@webroot/uploads/' . $newname);
                $attach->task_id = $newTask->id;
                $attach->path = $newname;
                $attach->title = $file->baseName . '.' . $file->getExtension();
                $attach->size = $file->size;
                if (!$attach->save()) {
                    throw new NoUploadFileException('Не удалось загрузить вложения');
                }
            }
        }
    }
}
