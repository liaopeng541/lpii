<?php

namespace lpsoft\lpii;
/**
 * v1 模块
 */
class lpii extends \yii\base\Module
{
    public $controllerNamespace = 'lpsoft\lpii\controllers';
    public $defaultRoute="app/index";
    public function init()
    {
        parent::init();
    }
}
