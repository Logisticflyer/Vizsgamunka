<?php

namespace tests\unit\services;

use PHPUnit\Framework\TestCase;
use app\services\UserService;
use app\models\SignupForm;
use app\models\User;
use app\repositories\UserRepositoryInterface;

/**
 * Class UserServiceTest
 *
 * Felelősség: A UserService regisztrációs logikájának automatizált,
 * izolált tesztelése szimulált (Mock) Repository és Mock DB segítségével.
 */
class UserServiceTest extends TestCase
{
    /**
     * Előkészítés minden egyes teszt futtatása előtt.
     * Mockoljuk a Yii2 adatbázis komponenst és sémát, hogy a teszt 
     * teljesen független legyen a fizikai MySQL szervertől.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mock TableSchema a User ActiveRecord attribútum-ellenőrzéséhez
        $tableSchema = new \yii\db\TableSchema();
        $tableSchema->columns = [
            'id' => new \yii\db\ColumnSchema(['name' => 'id', 'type' => 'integer']),
            'username' => new \yii\db\ColumnSchema(['name' => 'username', 'type' => 'string']),
            'email' => new \yii\db\ColumnSchema(['name' => 'email', 'type' => 'string']),
            'password_hash' => new \yii\db\ColumnSchema(['name' => 'password_hash', 'type' => 'string']),
            'auth_key' => new \yii\db\ColumnSchema(['name' => 'auth_key', 'type' => 'string']),
            'status' => new \yii\db\ColumnSchema(['name' => 'status', 'type' => 'integer']),
            'created_at' => new \yii\db\ColumnSchema(['name' => 'created_at', 'type' => 'integer']),
            'updated_at' => new \yii\db\ColumnSchema(['name' => 'updated_at', 'type' => 'integer']),
            'auth_key_expire' => new \yii\db\ColumnSchema(['name' => 'auth_key_expire', 'type' => 'integer']),
        ];

        $schemaMock = $this->getMockBuilder(\yii\db\mysql\Schema::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTableSchema'])
            ->getMock();
        $schemaMock->method('getTableSchema')->willReturn($tableSchema);

        // Javítás: Csak a létező 'getSchema' metódust konfiguráljuk a Connection-ön
        $dbMock = $this->getMockBuilder(\yii\db\Connection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSchema'])
            ->getMock();
        $dbMock->method('getSchema')->willReturn($schemaMock);

        // Beállítjuk a mockolt db komponenst a Yii konténerbe
        \Yii::$app->set('db', $dbMock);
    }

    /**
     * Teszteli, hogy érvénytelen űrlap esetén a regisztráció null-t ad-e vissza.
     */
    public function testRegisterReturnsNullWhenFormIsInvalid(): void
    {
        $repositoryMock = $this->createMock(UserRepositoryInterface::class);
        $repositoryMock->expects($this->never())->method('save');

        $userService = new UserService($repositoryMock);

        $form = new SignupForm();
        $form->username = ''; 
        $form->email = 'invalid-email';
        $form->password = '123';

        $result = $userService->register($form);

        $this->assertNull($result);
    }

    /**
     * Teszteli a sikeres regisztrációt érvényes adatok esetén.
     */
    public function testRegisterSucceedsWithValidData(): void
    {
        $repositoryMock = $this->createMock(UserRepositoryInterface::class);
        
        $repositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $userService = new UserService($repositoryMock);

        $form = $this->getMockBuilder(SignupForm::class)
            ->onlyMethods(['validate'])
            ->getMock();

        $form->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $form->username = 'tesztelek';
        $form->email = 'teszt@example.com';
        $form->password = 'Titkos123';

        $user = $userService->register($form);

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('tesztelek', $user->username);
        $this->assertEquals('teszt@example.com', $user->email);
        $this->assertEquals(User::STATUS_ACTIVE, $user->status);
        $this->assertNotEmpty($user->auth_key);
        $this->assertTrue($user->validatePassword('Titkos123'));
    }
}