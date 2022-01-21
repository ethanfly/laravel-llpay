## 连连支付sdk for laravel

1. 安装
``composer require ethan/laravel-llpay``
2. 发布配置文件
``php artisan vendor:publish --provider="Ethan\LianLianPay\LianLianPayServiceProvider"``
3. 修改配置文件``config/lianlianpay.php ``
4. 使用方式
```php

LianLianPay::buildRequestJSON(array $para_temp, string $llpay_gateway_new);
```

[连连开放平台文档](https://open.lianlianpay.com/apis/get-started.html)
