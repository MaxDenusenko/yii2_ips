<?php

namespace backend\forms\core;

use core\entities\Core\PaymentMethod;
use core\entities\User\User;
use core\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\Order;

/**
 * OrderSearch represents the model behind the search form of `core\entities\Core\Order`.
 */
class OrderSearch extends Model
{
    public $id;
    public $user_id;
    public $status;
    public $created;
    public $updated;
    public $type;
    public $amount;
    public $comment;
    public $paymentMethod;
    public $paymentStatus;

    public $created_date_from;
    public $created_date_to;

    public $updated_date_from;
    public $updated_date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created', 'updated', 'type', 'paymentMethod'], 'integer'],
            [['comment', 'user_id'], 'safe'],
            [['amount'], 'double'],
            [['created_date_from', 'created_date_to', 'updated_date_from', 'updated_date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function typeList()
    {
        return OrderHelper::typeList();
    }

    public function statusList()
    {
        return OrderHelper::statusList();
    }
    public function paymentList()
    {
        return OrderHelper::paymentList();
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
        $query = Order::find()->joinWith(['orderItems', 'user', 'renewalOrderItems', 'additionalOrderItems', 'paymentMethod']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user_id'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC],
            'desc' => [User::tableName().'.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['paymentMethod'] = [
            'asc' => [PaymentMethod::tableName().'.id' => SORT_ASC],
            'desc' => [PaymentMethod::tableName().'.id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            Order::tableName().'.status' => $this->status,
            Order::tableName().'.created' => $this->created,
            Order::tableName().'.updated' => $this->updated,
            Order::tableName().'.type' => $this->type,
        ]);

        $query->andFilterWhere(['like', Order::tableName().'.comment', $this->comment])
            ->andFilterWhere(['like', Order::tableName().'.amount', $this->amount])

            ->andFilterWhere(['like', User::tableName().'.username', $this->user_id])

            ->andFilterWhere(['like', PaymentMethod::tableName().'.id', $this->paymentMethod])

            ->andFilterWhere(['>=', 'created', $this->created_date_from ? strtotime($this->created_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created', $this->created_date_to ? strtotime($this->created_date_to . ' 23:59:59') : null])

            ->andFilterWhere(['>=', 'updated', $this->updated_date_from ? strtotime($this->updated_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'updated', $this->updated_date_to ? strtotime($this->updated_date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
