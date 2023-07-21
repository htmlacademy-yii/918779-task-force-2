<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use yii\db\ActiveQuery;

class TaskFilterForm extends Model {

    public const PERIOD_HOUR = 1;
    public const PERIOD_HALF_DAY = 12;
    public const PERIOD_DAY = 24;
    public const PERIOD_DEFAULT = 0;

    const PERIOD_VALUES = [
        '0' => 'Без ограничений',
        '1' => '1 час',
        '12'  => '12 часов',
        '24' => '24 часа'
    ];

    public const NO_RESPONSE = 'Без откликов';

    public $categories = [];
    public $remoteWork;
    public $noResponse;
    public $period;

    public function getTasks(): ActiveQuery
    {
        $tasks = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->joinWith(['category', 'city', 'responses'])
            ->orderBy(['creation' => SORT_DESC]);
        return $tasks;
    }

    private function applyPeriod($tasks): ActiveQuery
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

    public function apply(): ActiveQuery
    {
        $tasks = $this->getTasks();

        if (count($this->categories) > 0) {
            $tasks->andWhere(['category_id' => $this->categories]);
        }

        if ($this->noResponse) {
            $tasks->andWhere(['task_id' => null]);
        }

        if ($this->period) {
            $tasks = $this->applyPeriod($tasks);
        }

        return $tasks;
    }

    public function attributeLabels(): array {
        return [
            'noResponse' => self::NO_RESPONSE,
        ];
    }

    public function rules() {
        return [
            [['categories'], 'default', 'value' => []],
            [['categories'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'id']]],
            ['period', 'in', 'range' => [self::PERIOD_HOUR, self::PERIOD_HALF_DAY, self::PERIOD_DAY, self::PERIOD_DEFAULT]],
            ['noResponse', 'boolean'],
        ];
    }
}
