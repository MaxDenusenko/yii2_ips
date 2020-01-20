<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
<!--        <div class="user-panel">-->
<!--            <div class="pull-left image">-->
<!--                <img src="--><?//= $directoryAsset ?><!--/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>-->
<!--            </div>-->
<!--            <div class="pull-left info">-->
<!--                <p>Alexander Pierce</p>-->
<!---->
<!--                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
<!--            </div>-->
<!--        </div>-->

        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">-->
<!--            <div class="input-group">-->
<!--                <input type="text" name="q" class="form-control" placeholder="Search..."/>-->
<!--              <span class="input-group-btn">-->
<!--                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>-->
<!--                </button>-->
<!--              </span>-->
<!--            </div>-->
<!--        </form>-->
        <!-- /.search form -->
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Management', 'options' => ['class' => 'header']],
                    ['label' => 'Users', 'icon' => 'user-o', 'url' => ['/user/index'], 'active' => Yii::$app->controller->id == 'user'],
                    ['label' => 'Menus', 'icon' => 'folder-o', 'url' => ['/menu/index-root-node'], 'active' => Yii::$app->controller->id == 'menu'],
                    ['label' => 'Tariffs', 'icon' => 'folder-o', 'url' => ['/core/tariff/index'], 'active' => Yii::$app->controller->id == 'core/tariff'],
                    ['label' => 'User - Tariff', 'icon' => 'folder-o', 'url' => ['/core/tariff-assignment/index'], 'active' => Yii::$app->controller->id == 'core/tariff-assignment'],
                    ['label' => 'Tariff defaults', 'icon' => 'folder-o', 'url' => ['/core/tariff-defaults/index'], 'active' => Yii::$app->controller->id == 'core/tariff-defaults'],
                    ['label' => 'RBAC', 'icon' => 'folder', 'items' => [
                        ['label' => 'Route', 'icon' => 'file-o', 'url' => ['/rbac/route'], 'active' => Yii::$app->controller->id == 'route'],
                        ['label' => 'Permission', 'icon' => 'file-o', 'url' => ['/rbac/permission'], 'active' => Yii::$app->controller->id == 'permission'],
                        ['label' => 'Role', 'icon' => 'file-o', 'url' => ['/rbac/role'], 'active' => Yii::$app->controller->id == 'role'],
                        ['label' => 'Assignment', 'icon' => 'file-o', 'url' => ['/rbac/assignment'], 'active' => Yii::$app->controller->id == 'assignment'],
                    ]],
                ],
            ]
        ) ?>

    </section>

</aside>
