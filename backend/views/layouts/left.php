<aside class="main-sidebar">
    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => Yii::t('frontend', 'Management'), 'options' => ['class' => 'header']],
                    ['label' => Yii::t('frontend', 'Users'), 'icon' => 'user-o', 'url' => ['/user/index'], 'active' => Yii::$app->controller->id == 'user'],
//                    ['label' => 'Menus', 'icon' => 'folder-o', 'url' => ['/menu/index-root-node'], 'active' => Yii::$app->controller->id == 'menu'],
                    ['label' => Yii::t('frontend', 'Tariffs'), 'icon' => 'folder-o', 'url' => ['/core/tariff/index'], 'active' => Yii::$app->controller->id == 'core/tariff'],
                    ['label' => Yii::t('frontend', 'Tariffs (ordered)'), 'icon' => 'folder-o', 'url' => ['/core/tariff-assignment/index'], 'active' => Yii::$app->controller->id == 'core/tariff-assignment'],
                    ['label' => Yii::t('frontend', 'FAQ'), 'icon' => 'folder-o', 'url' => ['/faq/index'], 'active' => Yii::$app->controller->id == 'faq'],
                    ['label' => Yii::t('frontend', 'Pages'), 'icon' => 'folder-o', 'url' => ['/fragments/index'], 'active' => Yii::$app->controller->id == 'fragments'],
                    ['label' => Yii::t('frontend', 'Tariff Categories'), 'icon' => 'folder-o', 'url' => ['/core/category-tariffs/index'], 'active' => Yii::$app->controller->id == 'core/category-tariffs'],
                    ['label' => Yii::t('frontend', 'Currencies'), 'icon' => 'folder-o', 'url' => ['/core/currency/index'], 'active' => Yii::$app->controller->id == 'core/currency'],
                    ['label' => Yii::t('frontend', 'Orders'), 'icon' => 'folder-o', 'url' => ['/core/order/index'], 'active' => Yii::$app->controller->id == 'core/order'],
                    ['label' => Yii::t('frontend', 'Payment methods'), 'icon' => 'folder-o', 'url' => ['/core/payment-method/index'], 'active' => Yii::$app->controller->id == 'core/payment-method'],
                    ['label' => Yii::t('frontend', 'Coupons'), 'icon' => 'folder-o', 'url' => ['/core/coupons/index'], 'active' => Yii::$app->controller->id == 'core/coupons'],
                    ['label' => Yii::t('frontend', 'Using coupons'), 'icon' => 'folder-o', 'url' => ['/core/coupon-uses/index'], 'active' => Yii::$app->controller->id == 'core/coupon-uses'],
                    ['label' => Yii::t('frontend', 'News'), 'icon' => 'folder-o', 'url' => ['/news/index'], 'active' => Yii::$app->controller->id == 'news'],
//                    ['label' => 'Tariff defaults', 'icon' => 'folder-o', 'url' => ['/core/tariff-defaults/index'], 'active' => Yii::$app->controller->id == 'core/tariff-defaults'],
//                    ['label' => 'RBAC', 'icon' => 'folder', 'items' => [
//                        ['label' => 'Route', 'icon' => 'file-o', 'url' => ['/rbac/route'], 'active' => Yii::$app->controller->id == 'route'],
//                        ['label' => 'Permission', 'icon' => 'file-o', 'url' => ['/rbac/permission'], 'active' => Yii::$app->controller->id == 'permission'],
//                        ['label' => 'Role', 'icon' => 'file-o', 'url' => ['/rbac/role'], 'active' => Yii::$app->controller->id == 'role'],
//                        ['label' => 'Assignment', 'icon' => 'file-o', 'url' => ['/rbac/assignment'], 'active' => Yii::$app->controller->id == 'assignment'],
//                    ]],
                ],
            ]
        ) ?>

    </section>

</aside>
