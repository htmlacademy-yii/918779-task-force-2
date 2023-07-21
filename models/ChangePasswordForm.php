<?php

namespace app\models;

use yii;
use app\models\User;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $current_password;
    public $new_password;
    public $repeat_password;
    public $contacts;

    public function rules()
    {
        return
        [
            [['new_password', 'repeat_password', 'current_password', 'contacts'], 'string'],
            [['repeat_password'], 'compare', 'compareAttribute'=>'new_password'],
            ['new_password', 'checkPassword'],
            [['contacts'], 'default', 'value' => 'show'],
            ['current_password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return
        [
            'current_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'repeat_password' => 'Повторите новый пароль',
            'contacts' => 'Скрыть контакты'
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
          $user = Yii::$app->user->getIdentity();
            if (!$user->validatePassword($this->current_password)) {
                $this->addError($attribute, 'Неправильный пароль');
            }
        }
    }

    public function checkPassword($attribute, $params)
    {
        if ($this->current_password === $this->new_password)
        {
            $this->addError($attribute, 'Введите пароль отличный от текущего');
        }

        if (empty($this->current_password))
        {
            $this->addError($attribute, 'Введите текущий парлоль');
        }
    }



    public function changePassword()
    {
        $user = User::findOne(Yii::$app->user->getId());
    
        if ($this->new_password)
        {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->new_password);     
        }
        $user->contacts = $this->contacts ? User::HIDE_CONTACTS : User::SHOW_CONTACTS;

        return $user->save();
    }
}