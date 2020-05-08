<?php

namespace panix\mod\markup;

use panix\mod\markup\models\Markup;
use Yii;
use yii\base\BootstrapInterface;
use panix\engine\WebModule;
use panix\mod\admin\widgets\sidebar\BackendNav;

class Module extends WebModule implements BootstrapInterface
{

    public $icon = 'discount';

    /**
     * @var null
     */
    public $discounts = null;
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app->id != 'console' && $this->discounts === null) {

            $this->discounts = Markup::find()
                ->published()
                ->all();
        }
    }

    public function getInfo()
    {
        return [
            'label' => Yii::t('markup/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => $this->icon,
            'description' => Yii::t('markup/default', 'MODULE_DESC'),
            'url' => ['/admin/markup/default/index'],
        ];
    }

    public function getAdminSidebar()
    {
        return (new BackendNav())->findMenu('shop')['items'];
    }

    public function getAdminMenu()
    {
        return [
            'shop' => [
                'items' => [
                    [
                        'label' => Yii::t('markup/default', 'MODULE_NAME'),
                        'url' => ['/admin/markup/default/index'],
                        'icon' => $this->icon,
                    ],
                ],
            ],
        ];
    }

}
