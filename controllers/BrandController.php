<?php

namespace app\controllers;

use app\models\Brand;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\repositories\BrandRepository;

/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * lists all Brand models.
     * 
     * @return string
     */

    public function actionIndex()
    {
        $brandRepository = new BrandRepository();
        $brandVMs = $brandRepository->getBrands();

        $dataProvider = new ArrayDataProvider([ // Itt ArrayDataProvider kell!
            'allModels' => $brandVMs,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brand model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $brandRepository = new BrandRepository();
        $modelVM = $brandRepository->getBrandbyId($id);

        if ($modelVM === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $modelVM,
        ]);
    }

    /**
     * Creates a new Brand model.
     * If creation is succesful, the browser will be redirected to the 'view' page.
     * @return string |\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BrandVM();

        if ($this->request->isPost) 
        {
            if ($model->load($this->request->post()))
            {
                $brandRepository = new BrandRepository();
                $brandRepository -> createBrand($model);

                return $this->redirect(['view', 'id' => $model->Id]);
            }
        }   
            

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Brand model.
     * If update is succesful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found 
     */

    public function actionUpdate($id)
    {
        $model = $this->findmodel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }   
    
    /**
     * Deletes an existing Brand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $brandRepository = new BrandRepository();

        $brandRepository->deleteBrandById($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Brand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = Brand::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}