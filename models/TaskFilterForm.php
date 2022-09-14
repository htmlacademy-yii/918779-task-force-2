<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Category;
use app\models\Task;
use app\models\City;
use app\models\Response;
use yii\db\ActiveQuery;
use yii\db\Expression;

class TaskFilterForm extends \yii\db\ActiveRecord {

    public const PERIOD_HOUR = 1;
    public const PERIOD_HALF_DAY = 12;
    public const PERIOD_DAY = 24;
    public const PERIOD_DEFAULT = 0;

    public const PERIOD_VALUES = [
        self::PERIOD_DEFAULT => 'Без ограничений',
        self::PERIOD_HOUR => '1 час',
        self::PERIOD_HALF_DAY  => '12 часов',
        self::PERIOD_DAY => '24 часа'
    ];

    public $categories = [];
    public $remoteWork;
    public $noResponse;
    public $period;

    public const NO_RESPONSE = 'Без откликов';

    public function attributeLabels() {
        return [
            'remoteWork' => 'Удаленная работа',
            'noResponse' => self::NO_RESPONSE,
        ];
    }

    public function rules() {
        return [
            [['categories'], 'exist', 'skipOnError' => true, 'targetClass' => '\app\models\Category', 'targetAttribute' => ['categories' => 'id']],
            ['period', 'in', 'range' => [self::PERIOD_HOUR, self::PERIOD_HALF_DAY, self::PERIOD_DAY, self::PERIOD_DEFAULT]],
            ['remoteWork', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true],
            ['noResponse', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true],
        ];
    }

    public function getTasks(): ActiveQuery
    {
        $tasks = Task::find()
        ->where(['status' => Task::STATUS_NEW])
        ->joinWith(['category', 'city', 'responses'])
        ->orderBy(['creation' => SORT_DESC]);

        return $tasks;
    }

    private function getPeriod($tasks): ActiveQuery
    {

        settype($this->period, 'integer');

        switch($this->period) {
            case self::PERIOD_HOUR:
                return $tasks->andWhere(['=', 'creation', "DATE_SUB(NOW(), INTERVAL self::PERIOD_HOUR HOUR)"]);

            case self::PERIOD_HALF_DAY:
                return $tasks->andWhere(['=', 'creation', "DATE_SUB(NOW(), INTERVAL self::PERIOD_HALF_DAY HOUR)"]);

            case self::PERIOD_DAY:
                return $tasks->andWhere(['=', 'creation', "DATE_SUB(NOW(), INTERVAL self::PERIOD_DAY HOUR)"]);
        };

    }

    public static function getCategories(): array
    {

        $categories = Category::find()->all();

        return $categories;
    }

    public function apply(): array
    {

        $tasks = $this->getTasks();

        $selected_categories = count($this->categories);

        if  ($selected_categories > 0) {
            $tasks->andWhere(['in', 'category_id', $this->categories]);
        }

        if ($this->remoteWork) {
            $tasks->andWhere(['city_id' => null]);
        }

        if ($this->noResponse) {
            $tasks->andWhere(['task_id' => null]);
        }

        if ($this->period) {
            $this->getPeriod($tasks);
        }

        $tasks->orderBy(['creation' => SORT_ASC]);

        return $tasks()->all;
    }
}
