<aside class="main-sidebar sidebar-dark-primary elevation-4">
    
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Terméktípusok', 'icon' => 'th', 'url' => ['producttype/index']],
                    ['label' => 'Brandek', 'icon' => 'th', 'url' => ['brand/index']],
                    ['label' => 'Termékek', 'icon' => 'th', 'url' => ['product/index']],
                    ['label' => 'Árak', 'icon' => 'th', 'url' => ['price/index']],
                    
                    // AZ ÚJ MENÜPONT AZ AJÁNLATKÉRÉSEK KEZELÉSÉHEZ:
                    [
                        'label' => 'Ajánlatkérések', 
                        'icon' => 'envelope-open-text', 
                        'url' => ['ajanlatkeres/index'],
                        'active' => Yii::$app->controller->id === 'ajanlatkeres'
                    ],

                    ['label' => 'DEV TOOLS', 'header' => true],
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                ],
            ]);
            ?>
        </nav>
    </div>
</aside>