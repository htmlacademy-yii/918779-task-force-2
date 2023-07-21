<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ActionWidget extends Widget
{
    public object $action;

    public function init()
    {
    }

    public function run()
    {
        return Html::a(Html::encode($this->action->getTitle()), '#', ['class' => $this->action->getClass(),
        'data-action' => $this->action->getData()]);
    }
}
