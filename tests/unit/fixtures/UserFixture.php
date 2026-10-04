<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

/**
 * UserFixture - A User modellhez tartozó tesztadatbázis-fixture.
 */
class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $dataFile = '@app/tests/unit/fixtures/data/user.php';
}