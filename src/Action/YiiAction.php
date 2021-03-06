<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) kcloze <pei.greet@qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kcloze\Jobs\Action;

use Kcloze\Jobs\Config;
use Kcloze\Jobs\JobObject;
use Kcloze\Jobs\Logs;
use yii\console\Application;

class YiiAction extends BaseAction
{
    private $logger=null;

    public function init()
    {
        $this->logger = Logs::getLogger(Config::getConfig()['logPath'] ?? []);
    }

    public function start(JobObject $JobObject)
    {
        $this->init();

        $application         = new Application(Config::getConfig()['framework']['config'] ?? []);
        $route               = strtolower($JobObject->jobClass) . '/' . $JobObject->jobMethod;
        $params              = $JobObject->jobParams;
        try {
            $application->runAction($route, $params);
            $this->logger->log('Action has been done, action content: ' . json_encode($jobData));
        } catch (\Exception $e) {
            $this->logger->log($e->getMessage(), 'error');
        }
        unset($application, $JobObject);
    }
}
