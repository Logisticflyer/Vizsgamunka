<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ai_tasks".
 *
 * @property int $id
 * @property int $user_id
 * @property string $prompt
 * @property string|null $response
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 */
class AiTasks extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ai_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['response'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'pending'],
            [['user_id', 'prompt', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['prompt', 'response'], 'string'],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'prompt' => Yii::t('app', 'Prompt'),
            'response' => Yii::t('app', 'Response'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AiTasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AiTasksQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(app\models\User::class, ['id' => 'user_id']);
    }

}
