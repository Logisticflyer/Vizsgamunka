<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\models\Model3d;
use app\models\Order3d; // <- ÚJ: Beérkező 3D megrendelések modellje

class Model3dController extends Controller
{
    /**
     * Szigorú Admin hozzáférés ellenőrzés
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username !== 'admin') {
            Yii::$app->session->setFlash('error', 'Ez a felület csak az adminisztrátor számára érhető el.');
            return $this->redirect(['/site/index'])->send();
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Admin Dashboard: Beérkező munkák (Order3d) és közvetlen modellek listázása
     */
    public function actionIndex()
    {
        // 1. Ügyfelek által beküldött 3D megrendelések
        $orders = Order3d::find()->orderBy(['created_at' => SORT_DESC])->all();

        // 2. Közvetlenül feltöltött 3D modellek
        $models = Model3d::find()->orderBy(['created_at' => SORT_DESC])->all();
        $model = new Model3d();

        return $this->render('index', [
            'orders' => $orders,
            'models' => $models,
            'model' => $model,
        ]);
    }

    /**
     * 🟢 ÚJ: Beérkező munka státuszának frissítése (pl. approved, rejected, in_progress, completed)
     */
    public function actionUpdateStatus($id, $status)
    {
        $order = Order3d::findOne($id);

        if ($order) {
            $validStatuses = ['draft', 'pending', 'approved', 'rejected', 'in_progress', 'completed'];
            if (in_array($status, $validStatuses)) {
                $order->status = $status;
                if ($order->save(false)) {
                    Yii::$app->session->setFlash('success', 'A megrendelés státusza frissítve: ' . strtoupper($status));
                } else {
                    Yii::$app->session->setFlash('error', 'Hiba történt a státusz frissítésekor.');
                }
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * 🔴 ÚJ: Beérkező megrendelés (Order3d) és csatolt fájljainak törlése
     */
    public function actionDeleteOrder($id)
    {
        $order = Order3d::findOne($id);

        if ($order) {
            // Csatolt 3D modell törlése a lemezről
            if ($order->model_file) {
                $modelPath = Yii::getAlias('@webroot/') . $order->model_file;
                if (file_exists($modelPath) && is_file($modelPath)) {
                    @unlink($modelPath);
                }
            }

            // Csatolt AI 2D koncepciókép törlése a lemezről
            if ($order->concept_image) {
                $conceptPath = Yii::getAlias('@webroot/') . $order->concept_image;
                if (file_exists($conceptPath) && is_file($conceptPath)) {
                    @unlink($conceptPath);
                }
            }

            if ($order->delete()) {
                Yii::$app->session->setFlash('success', 'A megrendelés sikeresen törölve!');
            } else {
                Yii::$app->session->setFlash('error', 'Hiba történt az adatbázis törléskor.');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * 3D Fájl Feltöltés kezelése (.glb, .gltf) - Admin manuális feltöltésekhez
     */
    public function actionUpload()
    {
        $model = new Model3d();

        if (Yii::$app->request->isPost) {
            $model->title = Yii::$app->request->post('title');
            $model->description = Yii::$app->request->post('description');
            $file = UploadedFile::getInstanceByName('file3d');

            if ($file) {
                // 1. Mappa útvonalának összeállítása
                $uploadDir = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '3d';

                // 2. Ha '3d' nevű FÁJL létezik a mappa helyén, automatikusan töröljük
                if (file_exists($uploadDir) && !is_dir($uploadDir)) {
                    unlink($uploadDir);
                }

                // 3. Mappa létrehozása, ha még nem létezik
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // 4. Fájl elmentése
                $fileName = time() . '_' . $file->baseName . '.' . $file->extension;
                $targetFile = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

                if ($file->saveAs($targetFile)) {
                    $model->file_path = 'uploads/3d/' . $fileName;
                    $model->file_type = strtolower($file->extension);
                    $model->file_size = $file->size;
                    $model->user_id = Yii::$app->user->id ?? null;

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', '3D modell sikeresen feltöltve!');
                    } else {
                        Yii::$app->session->setFlash('error', 'Hiba az adatbázis mentéskor.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Nem sikerült elmenteni a fájlt a lemezre.');
                }
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * 3D Modell és a hozzá tartozó fizikai fájl törlése
     */
    public function actionDelete($id)
    {
        $model = Model3d::findOne($id);

        if ($model) {
            // 1. Töröljük a fizikai fájlt a lemezről, ha létezik
            $filePath = Yii::getAlias('@webroot') . '/' . $model->file_path;
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }

            // 2. Töröljük a rekordsort az adatbázisból
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Modell sikeresen törölve!');
            } else {
                Yii::$app->session->setFlash('error', 'Hiba történt az adatbázis törléskor.');
            }
        }

        return $this->redirect(['index']);
    }
}