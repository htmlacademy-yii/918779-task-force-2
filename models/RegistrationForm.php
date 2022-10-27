<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
use yii;

class RegistrationForm extends Model {

    public $name;
    public $email;
    public $password;
    public $repeat_password;
    public $city_id;
    public $role;

    public const ROLE_DEFAULT = 'executor';
    public const ROLE_CUSTOMER = 'customer';

    private function applyRole()
    {
        $role = self::ROLE_DEFAULT;

        if (!$this->role) {
            $role = self::ROLE_CUSTOMER;
        }

        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'repeat_password', 'city_id'], 'required', 'message' => 'Error description is here'],
            [['city_id'], 'integer'],
            [['role'], 'string'],
            [['name', 'email', 'password', 'repeat_password'], 'string', 'max' => 128],
            [['repeat_password'], 'compare', 'compareAttribute'=>'password'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
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
     *
     */
    public function registration()
    {
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($user->password);
        $user->role = $this->applyRole();
        $user->city_id = $this->city_id;

        return $user->save(false);
    }
}
