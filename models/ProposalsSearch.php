<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proposals;

/**
 * ProposalsSearch represents the model behind the search form of `app\models\Proposals`.
 */
class ProposalsSearch extends Proposals
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_client_id', 'fk_user_id', 'fk_status_id', 'created_by', 'status', 'updated_by'], 'integer'],
            [['proposal_text', 'created_at', 'ip', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Proposals::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_client_id' => $this->fk_client_id,
            'fk_user_id' => $this->fk_user_id,
            'fk_status_id' => $this->fk_status_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'proposal_text', $this->proposal_text])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
