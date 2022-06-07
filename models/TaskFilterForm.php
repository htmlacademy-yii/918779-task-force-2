<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Category;

class TaskFilterForm extends Model {
    const PERIOD_VALUES = [
        '0' => 'Без ограничений',
        '1' => '1 час',
        '12'  => '12 часов',
        '24' => '24 часа'
    ];

    const ACTION_DEFAULT = 'Без откликов';

    public $categories = [];
    public $remoteWork = 0;
    public $noResponse = 0;
    public $period;

    public function attributeLabels() {
        return [
            'remoteWork' => 'Удаленная работа',
            'noResponse' => self::ACTION_DEFAULT,
        ];
    }

    public function rules() {
        return [
            [['categories', 'remoteWork', 'noResponse', 'period'], 'safe'],
        ];
    }
}
