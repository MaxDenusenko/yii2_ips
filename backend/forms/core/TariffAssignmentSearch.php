<?php

namespace backend\forms\core;

use core\entities\Core\Tariff;
use core\entities\User\User;
use core\helpers\TariffAssignmentHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\TariffAssignment;

/**
 * TariffAssignmentSearch represents the model behind the search form of `core\entities\Core\TariffAssignment`.
 */
class TariffAssignmentSearch extends TariffAssignment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tariff_id', 'user_id', 'status'], 'safe'],
        ];
    }

    public function statusList(): array
    {
        return TariffAssignmentHelper::statusList();
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
        $query = TariffAssignment::find()->joinWith(['tariff', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['tariff_id'] = [
            'asc' => [User::tableName().'.name' => SORT_ASC],
            'desc' => [Tariff::tableName().'tariff.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['user_id'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', Tariff::tableName().'.name', $this->tariff_id])
        ->andFilterWhere(['like', User::tableName().'.username', $this->user_id]);

        return $dataProvider;
    }
}
