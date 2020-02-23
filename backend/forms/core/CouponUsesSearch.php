<?php

namespace backend\forms\core;

use core\entities\Core\Coupons;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\CouponUses;

/**
 * CouponUsesSearch represents the model behind the search form of `core\entities\Core\CouponUses`.
 */
class CouponUsesSearch extends Model
{
    public $id;
    public $coupon_id;
    public $user_id;
    public $date_use;
    public $tariff_assignment_hash_id;
    public $sum;

    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum'], 'double'],
            [['id'], 'integer'],
            [['date_use', 'coupon_id', 'user_id', 'tariff_assignment_hash_id'], 'safe'],
            [['date_to', 'date_from'], 'date', 'format' => 'php:Y-m-d'],
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
        $query = CouponUses::find()->joinWith(['user', 'coupon', 'tariffAssignment']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user_id'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC],
            'desc' => [User::tableName().'.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['coupon_id'] = [
            'asc' => [Coupons::tableName().'.code' => SORT_ASC],
            'desc' => [Coupons::tableName().'.code' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tariff_assignment_hash_id'] = [
            'asc' => [TariffAssignment::tableName().'.tariff.name' => SORT_ASC],
            'desc' => [TariffAssignment::tableName().'.tariff.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            CouponUses::tableName().'.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', User::tableName().'.username', $this->user_id])
            ->andFilterWhere(['=', Coupons::tableName().'.code', $this->coupon_id])
            ->andFilterWhere(['like', CouponUses::tableName().'.sum', $this->sum])
            ->andFilterWhere(['like', TariffAssignment::tableName().'.hash_id', $this->tariff_assignment_hash_id])
            ->andFilterWhere(['>=', 'date_use', $this->date_from ? $this->date_from . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'date_use', $this->date_to ? $this->date_to . ' 23:59:59' : null]);

        return $dataProvider;
    }
}
