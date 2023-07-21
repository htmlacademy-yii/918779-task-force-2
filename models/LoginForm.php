<?php

namespace app\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $currentUser;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    /**
     * Get User
     *
     */
    public function getUser()
    {
        if ($this->currentUser === null) {
            $this->currentUser = User::findOne(['email' => $this->email]);
        }

        return $this->currentUser;
    }
}
