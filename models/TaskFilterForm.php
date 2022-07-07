<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Category;

class TaskFilterForm extends Model {

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

    public const ACTION_DEFAULT = 'Без откликов';

    public $categories = [];
    public $remoteWork;
    public $noResponse;
    public $period;

    public function attributeLabels() {
        return [
            'remoteWork' => 'Удаленная работа',
            'noResponse' => self::ACTION_DEFAULT,
        ];
    }

    public function rules() {
        return [
            ['categories', 'exist'],
            ['period', 'in', 'range' => [self::PERIOD_HOUR, self::PERIOD_HALF_DAY, self::PERIOD_DAY, self::PERIOD_DEFAULT]],
            [['categories', 'remoteWork', 'noResponse', 'period'], 'safe'],
        ];
    }
}
