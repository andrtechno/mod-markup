# Модуль скидки  [EN description](README.md)

Модуль наценок для "Pixelion CMS"

[![Latest Stable Version](https://poser.pugx.org/panix/mod-markup/v/stable)](https://packagist.org/packages/panix/mod-markup)
[![Total Downloads](https://poser.pugx.org/panix/mod-markup/downloads)](https://packagist.org/packages/panix/mod-markup)
[![Monthly Downloads](https://poser.pugx.org/panix/mod-markup/d/monthly)](https://packagist.org/packages/panix/mod-markup)
[![Daily Downloads](https://poser.pugx.org/panix/mod-markup/d/daily)](https://packagist.org/packages/panix/mod-markup)
[![Latest Unstable Version](https://poser.pugx.org/panix/mod-markup/v/unstable)](https://packagist.org/packages/panix/mod-markup)
[![License](https://poser.pugx.org/panix/mod-markup/license)](https://packagist.org/packages/panix/mod-markup)


## Установка

Предпочтительным способом установки этого модуля является [composer](http://getcomposer.org/download/).

#### Либо запустите

```
php composer require --prefer-dist panix/mod-markup "*"
```

или добавить

```
"panix/mod-markup": "*"
```

в раздел require `composer.json` файла.

#### Добавить в веб конфигурацию.
```
'modules' => [
    'markup' => ['class' => 'panix\markup\Module'],
],
```

#### Миграция
```
php yii migrate --migrationPath=vendor/panix/mod-markup/migrations
```


## См. также
- [Pixelion CMS](https://pixelion.com.ua)
- [Модуль сравнения Github](https://https://github.com/andrtechno/mod-compare)
- [Модуль желайний Github](https://https://github.com/andrtechno/mod-wishlist)
- [Модуль карзина Github](https://https://github.com/andrtechno/mod-cart)
- [Модуль магазин Github](https://https://github.com/andrtechno/mod-shop)

------------------------

<i>Content Management System "Pixelion CMS"</i>  
[www.pixelion.com.ua](https://pixelion.com.ua)