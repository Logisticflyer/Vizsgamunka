<?php

namespace tests\unit\models;

use app\models\User;
use yii\test\ActiveFixture;

/**
 * UserTest - A User modell unit tesztje.
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Beállítja a teszthez szükséges adatbázis-fixtúrákat a gyári ActiveFixture segítségével.
     */
    public function _fixtures()
    {
        return [
            'users' => [
                'class' => ActiveFixture::class,
                'modelClass' => User::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    public function testFindUserById()
    {
        verify($user = User::findIdentity(100))->notEmpty();
        verify($user->username)->equals('admin');

        verify(User::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = User::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->username)->equals('admin');

        verify(User::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        verify($user = User::findByUsername('admin'))->notEmpty();
        verify(User::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = User::findByUsername('admin');
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();
    }
}