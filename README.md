Various custom plug-ins used by the project
===========================================
Various custom plug-ins used by the project

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist adamyu/helper "*"
```

or add

```
"adamyu/helper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \adamyu\helper\AutoloadExample::widget(); ?>```