<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\DbManager;
use app\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        if (!$auth instanceof DbManager){
            $this->stdout("Error: authManager must be DbManager\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("RBAC tisztitasa (regi adatok torlese)... ");
        $auth->removeAll();
        $this->stdout("[OK]\n");
        
        // Admin szerepkör létrehozása
        $this->stdout("Admin szerepkor letrehozasa... ");
        $adminRole = $auth->createRole('admin');
        $adminRole->description = 'Rendszeradminisztrator';
        $auth->add($adminRole);
        $this->stdout("[OK]\n");

        // Permissziók létrehozása (Tiszta névvel, '@' nélkül)
        $this->stdout("Jogosultsagok letrehozasa... ");
        $managePosts = $auth->createPermission('managePosts');
        $managePosts->description = 'Ajanlatkeresek es felhasznalok kezelese';
        $auth->add($managePosts);
        $this->stdout("[OK]\n");

        // Kapcsolatok kiépítése
        $this->stdout("Jogosultsagok hozzarendelese az Adminhoz... ");
        $auth->addChild($adminRole, $managePosts);
        $this->stdout("[OK]\n");

        // Alapértelmezett Admin hozzárendelés az 1-es ID-jú felhasználóhoz
        $this->stdout("Admin szerepkor dedikalasa az 1-es ID-u felhasznalonak... ");
        try {
            $auth->assign($adminRole, 1);
            $this->stdout("[OK]\n");
        } catch (\Exception $e) {
            $this->stdout("[INFO] A szerepkor mar hozza van rendelve.\n");
        }

        $this->stdout("\nRBAC rendszer sikeresen inicializalva!\n");
        return ExitCode::OK;
    }

    public function actionAssign($roleName, $username)
    {
        $auth = Yii::$app->authManager;
        $user = User::find()->where(['username' => $username])->one();

        if (!$user) {
            $this->stdout("Hiba: Felhasznalo nem talalhato: $username\n");
            return ExitCode::DATAERR;
        }

        $role = $auth->getRole($roleName);
        if (!$role) {
            $this->stdout("Hiba: Szerepkor nem talalhato: $roleName\n");
            return ExitCode::DATAERR;
        }

        try {
            $auth->assign($role, $user->id);
            $this->stdout("Sikeres: '$roleName' szerepkor hozzarendelve '$username' felhasznalohoz.\n");
            return ExitCode::OK;
        } catch (\Exception $e) {
            $this->stdout("Info: A felhasznalonak mar van ilyen szerepkore.\n");
            return ExitCode::OK;
        }
    }
}