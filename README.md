Various custom plug-ins used by the project
===========================================
Various custom plug-ins used by the project

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist adamyxt/helper "*"
```

or add

```
"adamyxt/helper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

根据传输数据生成签名方式传递数据帮助类使用方法(API传输数据加密方式)  :

```php
    1配置组件
    'components' => [
        'encrypt' => [
            'class' => 'common\components\crypt\Encrypt',
            'key' => '123',
        ],
    ],
    key设置自己生成的
    2使用
    $encrypt = Yii::$app->get('encrypt');
    数据格式
    $data = [
                'sn' => 'sn123456789,
                'timestamp' => time(),
                'user_id' => 45,
                'type' => 2,
                'num' => 455,
    ];
    $sign = $encrypt->encrypt($data);
    $data['sign'] = $sign;
    将计算出的签名sign加入data并传输data
    ```