<?php

namespace panix\mod\markup;

use yii\web\AssetBundle;

class MarkupAsset extends AssetBundle {

    public $sourcePath = __DIR__.'/assets/admin';

    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $js = [
        'default.update.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
