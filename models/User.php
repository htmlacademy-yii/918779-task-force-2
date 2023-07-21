<?php

namespace app\models;

use Yii;
use app\models\City;
use Taskforce\Tasks;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $registration
 * @property string $name
 * @property string|null $birthday
 * @property string|null $avatar
 * @property string|null $phone
 * @property string $email
 * @property string|null $telegram
 * @property string|null $info
 * @property int $city_id
 * @property string|null $role
 * @property string|null $token
 * @property string $password
 * @property Category[] $categories
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Specialization[] $specializations
 * @property Task[] $tasks
 */
class User extends ActiveRecord implements IdentityInterface
{
    const HIDE_CONTACTS = 'hide';
    const SHOW_CONTACTS = 'show';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * {@inheritdoc}
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['registration', 'birthday'], 'safe'],
            [['name', 'email', 'city_id', 'password'], 'required'],
            [['info', 'role'], 'string'],
            [['city_id', 'token'], 'integer'],
            [['name', 'email'], 'string', 'max' => 128],
            [['avatar', 'password'], 'string', 'max' => 255],
            ['avatar', 'default', 'value' => 'img/avatars/1.png' ],
            [['phone'], 'string', 'max' => 12],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['telegram'], 'unique'],
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
            'registration' => 'Registration',
            'name' => 'Ваше имя',
            'birthday' => 'Birthday',
            'avatar' => 'Avatar',
            'phone' => 'Телефон',
            'email' => 'Email',
            'telegram' => 'Telegram',
            'info' => 'Info',
            'city_id' => 'Город',
            'role' => 'Role',
            'token' => 'VK User ID',
            'password' => 'Пароль',
            'repeat_password' => 'Повтор пароля',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('specialization', ['user_id' => 'id']);
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
        return $this->hasMany(Response::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Specializations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecializations()
    {
        return $this->hasMany(Specialization::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['user_id' => 'id']);
    }

    /**
     * Gets user statistic.
     *
     * @return array
     */
    public function getUserStats(): array
    {
        $totalReview = Review::find()->where(['user_id' => $this->id])->sum('stats');
        $countReview = Review::find()->where('stats > 0')->andWhere(['user_id' => $this->id])->count('stats');
        $countFailedTasks = Task::find()->where(['user_id' => $this->id, 'status' => Tasks::STATUS_FAILED])->count('id');
        if (($countReview + $countFailedTasks) > 0)
        {
            $this->stats = $totalReview / ($countReview + $countFailedTasks);
        }
        else {
            $this->stats = $totalReview; 
        }
        
        $this->save();

        $position = User::find()
        ->select('ROW_NUMBER() OVER (ORDER BY stats DESC)')
        ->indexBy('id')
        ->column();

        $userStats = [
            'count' => $countReview,
            'failed' => $countFailedTasks,
            'position' => $position[$this->id]
        ];

        return $userStats;
    }

    /**
     * Gets user age.
     *
     * @return int
     */
    public function getUserAge(): int
    {
        $userAge = date_diff(date_create(date('Y-m-d')), date_create($this->birthday));

        return $userAge->format('%y');
    }

        /**
     * Gets user status.
     *
     * @return string
     */

    public function getUserStatus(): string 
    {
        $userStatus = 'Открыт для новых заказов';
        $this->status = Tasks::USER_STATUS_FREE;

        $count = Task::find()->where(['user_id' => $this->id])->andWhere(['status' =>Tasks::STATUS_WORKING])->count();


        if ($count > 0)
        {
            $this->status = Tasks::USER_STATUS_BUSY;
            $userStatus = 'Занят';
        }           
        $this->save();
        
        return $userStatus;
    }
}
