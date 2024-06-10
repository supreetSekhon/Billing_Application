<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_checklist".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $status
 * @property string $ip
 * @property int $crt_by
 * @property string $crt_time
 * @property int $mod_by
 * @property string $mod_time
 */
class TaxRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_state', 'description'], 'required'],
            [['status', 'crt_by', 'mod_by'], 'integer'],
            [['tax_rate'], 'string', 'max' => 10],
            [['crt_time', 'mod_time'], 'safe'],
            [['title'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 1500],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_rate' => 'Tax Rate',
            'province_state' => 'Province/State',
            'description' => 'Description',
            'status' => 'Status',
            'ip' => 'Ip',
            'crt_by' => 'Crt By',
            'crt_time' => 'Crt Time',
            'mod_by' => 'Mod By',
            'mod_time' => 'Mod Time',
        ];
    }
}
