<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_3d".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property array|null $parameters
 * @property array|null $attachments
 * @property string|null $concept_image
 * @property string|null $model_file
 * @property array|null $chat_history
 * @property string $created_at
 * @property string $updated_at
 */
class Order3d extends ActiveRecord
{
    public static function tableName()
    {
        return 'order_3d';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['user_id'], 'integer'],
            [['description'], 'string'],
            [['status'], 'in', 'range' => ['draft', 'pending', 'approved', 'rejected', 'in_progress', 'completed']],
            [['parameters', 'attachments', 'chat_history'], 'safe'],
            [['title', 'concept_image', 'model_file'], 'string', 'max' => 255],
        ];
    }

    /**
     * JSON mezők automatikus kódolása és dekódolása
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->parameters = is_string($this->parameters) ? json_decode($this->parameters, true) : $this->parameters;
        $this->attachments = is_string($this->attachments) ? json_decode($this->attachments, true) : $this->attachments;
        $this->chat_history = is_string($this->chat_history) ? json_decode($this->chat_history, true) : $this->chat_history;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (is_array($this->parameters)) {
                $this->parameters = json_encode($this->parameters, JSON_UNESCAPED_UNICODE);
            }
            if (is_array($this->attachments)) {
                $this->attachments = json_encode($this->attachments, JSON_UNESCAPED_UNICODE);
            }
            if (is_array($this->chat_history)) {
                $this->chat_history = json_encode($this->chat_history, JSON_UNESCAPED_UNICODE);
            }
            return true;
        }
        return false;
    }

    /**
     * 🛡️ FIZIKAI VALIDÁCIÓS MOTOR (Sanity Check Engine)
     * Ellenőrzi, hogy az AI vagy a felhasználó által megadott paraméterek fizikailag megvalósíthatóak-e.
     */
    public static function validatePhysicsRules(array $params): array
    {
        $errors = [];
        $warnings = [];

        // 1. Csavar és Furat illeszkedés ellenőrzése
        if (isset($params['hole_diameter_mm'], $params['bolt_diameter_mm'])) {
            if ($params['bolt_diameter_mm'] > $params['hole_diameter_mm']) {
                $errors[] = sprintf(
                    "Fizikai képtelenség: A megadott csavar átmérője (%.1f mm) nagyobb, mint a furat átmérője (%.1f mm)!",
                    $params['bolt_diameter_mm'],
                    $params['hole_diameter_mm']
                );
            }
        }

        // 2. Minimum falvastagság ellenőrzése (3D nyomtatáshoz/gyártáshoz)
        if (isset($params['wall_thickness_mm']) && $params['wall_thickness_mm'] < 1.2) {
            $warnings[] = sprintf(
                "Figyelem: A falvastagság (%.1f mm) túl vékony. A stabil nyomtatáshoz legalább 1.2 mm ajánlott.",
                $params['wall_thickness_mm']
            );
        }

        // 3. Külső és Belső méret ellentmondás
        if (isset($params['outer_width_mm'], $params['inner_width_mm'])) {
            if ($params['inner_width_mm'] >= $params['outer_width_mm']) {
                $errors[] = "Fizikai képtelenség: A belső szélesség nem lehet nagyobb vagy egyenlő a külső szélességgel!";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }
}