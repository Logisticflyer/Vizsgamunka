<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ai_knowledge".
 *
 * @property int $id
 * @property string $category
 * @property string $title
 * @property string $content
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class AiKnowledge extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ai_knowledge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['category', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['category', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AiKnowledgeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AiKnowledgeQuery(get_called_class());
    }

}
