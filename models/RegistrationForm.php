<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
use app\models\City;
use yii;

class RegistrationForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $repeat_password;
    public $city_id;
    public $role;

    public const ROLE_DEFAULT = 'executor';
    public const ROLE_CUSTOMER = 'customer';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'repeat_password', 'city_id'], 'required'],
            [['city_id'], 'integer'],
            [['role', 'password', 'repeat_password', 'name', 'email'], 'string'],
            [['repeat_password'], 'compare', 'compareAttribute' => 'password'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            [['city_id'], 'exist', 'skipOnError' => true,
            'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'repeat_password' => 'Повтор пароля',
            'role' => 'Я собираюсь откликаться на заказы',
            'city_id' => 'Город',
        ];
    }

    /**
     * Registration
     *
     */
    public function registration()
    {
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->role = $this->role ? self::ROLE_DEFAULT : self::ROLE_CUSTOMER;
        $user->contacts = User::SHOW_CONTACTS;
        $user->city_id = $this->city_id;

        return $user->save();
    }
}
