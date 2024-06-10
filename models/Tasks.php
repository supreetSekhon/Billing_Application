<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int|null $assigned_to
 * @property string $title
 * @property string|null $description
 * @property int|null $status_id
 * @property string|null $due_date
 * @property string $created_at
 * @property int|null $created_by
 * @property string $ip
 * @property int $status
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @property Users $assignedTo
 * @property StatusValues $status0
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assigned_to', 'status_id', 'created_by', 'status', 'updated_by'], 'integer'],
            [['title'], 'required'],
            [['description'], 'string'],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 20],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['assigned_to' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusValues::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }
    public function beforeSave($insert) {
        $session = Yii::$app -> session;
        if ($insert) {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> created_by = $session -> get('userId');
          $this -> created_at = date('Y-m-d H:i:s');
        } else {
          $this -> ip = Yii::$app -> getRequest() -> getUserIp();
          $this -> updated_by = $session -> get('userId');
          $this -> updated_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assigned_to' => 'Assigned To',
            'title' => 'Title',
            'description' => 'Description',
            'status_id' => 'Status',
            'due_date' => 'Due Date',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'ip' => 'Ip',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[AssignedTo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        return $this->hasOne(Users::class, ['id' => 'assigned_to']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(StatusValues::class, ['id' => 'status_id']);
    }
}
