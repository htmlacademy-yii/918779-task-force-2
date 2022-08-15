<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Category;

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

    public function getPeriod($period) {
        settype($period, 'integer');

        switch($period) {
            case self::PERIOD_HOUR:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL self::PERIOD_HOUR HOUR)']);

            case self::PERIOD_HALF_DAY:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL self::PERIOD_HALF_DAY HOUR)']);

            case self::PERIOD_DAY:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL self::PERIOD_DAY HOUR)']);
        };
    }

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
}
