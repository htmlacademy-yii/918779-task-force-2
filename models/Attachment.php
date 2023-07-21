<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property int $id
 * @property int $task_id
 * @property string $title
 * @property string $path
 *
 * @property Task $task
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'title', 'path'], 'required'],
            [['task_id'], 'integer'],
            [['title', 'path'], 'string', 'max' => 255],
            [['task_id'], 'exist',
            'skipOnError' => true, 'targetClass' => Task::className(),
            'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'title' => 'Title',
            'path' => 'Path',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
