<?php

namespace backend\forms\core;

use core\helpers\CouponsHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\Coupons;

/**
 * CouponsSearch represents the model behind the search form of `core\entities\Core\CouponsHelper`.
 */
class CouponsSearch extends Coupons
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'number', 'per_cent', 'type'], 'integer'],
            [['code'], 'safe'],
        ];
    }

    public function typeList()
    {
        return CouponsHelper::typeList();
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
        $query = Coupons::find();

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
            'number' => $this->number,
            'per_cent' => $this->per_cent,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
