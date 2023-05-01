<?php

namespace app\models;

use \Taskforce\Exceptions\NoAvailableActionsException;

use \Taskforce\Actions\CancelAction;
use \Taskforce\Actions\RespondAction;
use \Taskforce\Actions\DoneAction;
use \Taskforce\Actions\RefusedAction;
use \Taskforce\Helpers\AvailableButton;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $creation
 * @property string $title
 * @property string $description
 * @property int|null $estimate
 * @property string|null $runtime
 * @property int $city_id
 * @property float|null $lat
 * @property float|null $lng
 * @property int $user_id
 * @property int $category_id
 * @property string|null $status
 *
 * @property Attachment[] $attachments
 * @property Category $category
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{

    const STATUS_NEW = 'new';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creation', 'runtime'], 'safe'],
            [['title', 'description', 'city_id', 'user_id', 'category_id'], 'required'],
            [['description', 'status'], 'string'],
            [['estimate', 'city_id', 'user_id', 'category_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creation' => 'Creation',
            'title' => 'Название',
            'description' => 'Подробности задания',
            'estimate' => 'Бюджет',
            'runtime' => 'Срок исполнения',
            'city_id' => 'Город',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Attachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
