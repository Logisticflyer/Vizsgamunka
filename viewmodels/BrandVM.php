<?php

namespace app\viewmodels;

use Yii;

class BrandVM extends Model
{
    public $Id;
    public $Name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id','Name'], 'required'],
            [['Id'], 'integer'],
            [['Name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'Brand azonosító',
            'Name' => 'Brand neve',
        ];
    }
}
?>