<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "e_signatures".
 *
 * @property int $id
 * @property int|null $fk_proposal_id
 * @property int|null $fk_client_id
 * @property string $signed_at
 * @property string $created_at
 * @property int|null $created_by
 * @property string $ip
 * @property int $status
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @property Clients $fkClient
 * @property Proposals $fkProposal
 */
class ESignatures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'e_signatures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_proposal_id', 'fk_client_id', 'created_by', 'status', 'updated_by'], 'integer'],
            [['signed_at', 'created_at', 'updated_at'], 'safe'],
            [['ip'], 'string', 'max' => 20],
            [['fk_proposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proposals::class, 'targetAttribute' => ['fk_proposal_id' => 'id']],
            [['fk_client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::class, 'targetAttribute' => ['fk_client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_proposal_id' => 'Fk Proposal ID',
            'fk_client_id' => 'Fk Client ID',
            'signed_at' => 'Signed At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'ip' => 'Ip',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[FkClient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkClient()
    {
        return $this->hasOne(Clients::class, ['id' => 'fk_client_id']);
    }

    /**
     * Gets query for [[FkProposal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkProposal()
    {
        return $this->hasOne(Proposals::class, ['id' => 'fk_proposal_id']);
    }
}
