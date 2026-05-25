<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class HashController extends Controller
{
    public function actionPassword($password)
    {
        $hash = Yii::$app->security->generatePasswordHash($password);
        $this->stdout("Password hash: " . $hash . "\n");
    }
}