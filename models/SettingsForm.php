<?php

namespace app\models;

use yii;
use yii\base\Model;
use app\models\User;
use app\models\Specialization;
use yii\web\UploadedFile;
use Taskforce\Exceptions\NoEditSettingsException;

class SettingsForm extends Model {

    public $name;
    public $email;
    public $birthday;
    public $phone;
    public $telegram;
    public $info;
    public $categories = [];

    const PROFILE = 'profile';
    const SECURITY = 'security';


        /**
     * @var UploadedFile
     */
    public $avatar;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email' ], 'required'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            ['phone', 'string', 'max' => 11],
            ['phone', 'match', 'pattern' => '/^\d+$/'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['birthday', 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<', 'type' => 'date'],
            [['avatar'], 'file', 'extensions' => 'gif, png, jpg'],
            [['avatar'], 'file', 'extensions' => 'gif, png, jpg', 'maxSize' => 1024*1024],        
            [['telegram'], 'string', 'max' => 64],
            [['info'], 'string'],
            [['categories'], 'default', 'value' => []],
            [['categories'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'id']]],
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
            'birthday' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'info' => 'Информация о себе',
            'categories' => 'Выбор специализации',
            'avatar' => 'Аватар',
        ];
    }

       public function editProfile()
    {
        $user = User::findOne(Yii::$app->user->getId());
        if ($this->name) 
        {
            $user->name = $this->name;  
        }

        if ($this->email) 
        {
            $user->email = $this->email;  
        }
        
        if ($this->birthday) 
        {
            $user->birthday = $this->birthday;  
        }
        
        if ($this->phone) 
        {
            $user->phone = $this->phone;   
        }

        if ($this->telegram) 
        {
            $user->telegram = $this->telegram;   
        }

        if ($this->info) 
        {
            $user->info = $this->info;     
        }  
       
        if ($this->avatar) 
        { 
            $this->avatar = UploadedFile::getInstance($this, 'avatar');
            if ($this->validate()) 
            {
                $newname = uniqid('avatar') . '.' . $this->avatar->getExtension();
                $this->avatar->saveAs('@webroot/uploads/avatars/' . $newname);
                $user->avatar = 'uploads/avatars/' . $newname;
            }   
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$user->save()){
                throw new NoEditSettingsException("Не удалось изменить настройки");
            }

            $this->getSpecialization($user);
            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $user;
    }

    public function getSpecialization($newSpecialization)
    {

       if (count($this->categories) > 0)
        {
            foreach($this->categories as $category)
            {
                $newSpecialization = new Specialization();
                $newSpecialization->user_id = Yii::$app->user->getId();
                $newSpecialization->category_id = $category;
                $newSpecialization->save();
            }
        }
    }
}
