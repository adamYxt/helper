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
-----

数据库批量修改封装帮助类使用方法(配合事物使用，次方法适用于批量修改数字型字段的加减)  :

```php
        $data = [
            1 => [
                'num' => [
                    'value' => 45,
                    'type' => MysqlHelper::UPDATE_PLUS
                ],
                'create_at' => time(),
                'ice_num' => 6
            ],
            2 => [
                'num' => [
                    'value' => 45,
                    'type' => MysqlHelper::UPDATE_MINUS
                ],
                'create_at' => time(),
                'ice_num' => 3
            ],
            3 => [
                'num' => 456,
                'ice_num' => 9,
                'create_at' => time()
            ],
            5 => [
                'num' => 12783,
                'ice_num' => 7,
                'create_at' => time()
            ],
        ];
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if (!MysqlHelper::batchUpdate('test', 'id', $data)) {
                throw new Exception(123);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            var_dump($e->getMessage());
        }
二维数组键值为where条件，每个修改的字段可以单独设置为替换，加，减，不设置默认为替换
```