<?php

namespace panix\mod\markup;

use panix\mod\markup\models\Markup;
use Yii;
use yii\base\BootstrapInterface;
use panix\engine\WebModule;
use app\web\themes\dashboard\sidebar\BackendNav;
use yii\console\Application;
use yii\db\Exception;

class Module extends WebModule implements BootstrapInterface
{

    public $icon = 'markups';

    /**
     * @var null
     */
    public $markups = null;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        if ($this->markups === null) {
            try {
                $this->markups = Markup::find()->published()->all();
            } catch (Exception $exception) {

            }
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
                        'visible' => Yii::$app->user->can('/markup/admin/default/index') || Yii::$app->user->can('/markup/admin/default/*')
                    ],
                ],
            ],
        ];
    }

}
