<?php

namespace app\repositories;

use app\models\Brand;
use app\viewmodels\BrandVM;
use Yii;

class BrandRepository
{
    public function getBrands()
    {
        $brands = Brand::find()->all();
        $brandVMs = [];
        
        foreach ($brands as $brand) {
            $brandVM = new BrandVM();
            $brandVM->Id = $brand->Id;
            $brandVM->Name = $brand->Name;
            $brandVMs[] = $brandVM;
        }
        return $brandVMs;
    }
    public function getBrandbyId($id)
    {
        $brand = Brand::findOne($id);
        if ($brand == null) {
            return null;
        }

        $brandVM = new BrandVM();
        $brandVM->Id = $brand->Id;
        $brandVM->Name = $brand->Name;

        return $brandVM;
    }    

    public function deleteBrandById($id)
    {
        $brand = Brand::findone($id);

        if ($brand !==null) {
            return $brand->delete();
        }
        return false;
    }

    public function createBrand($brandVM)
    {
        $brand = new Brand();
        $brand->Name = $brandVM->Name;
        
        return $brand->save();
    }
}
?>