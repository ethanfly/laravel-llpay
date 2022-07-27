## 连连支付sdk for laravel

1. 安装
``composer require ethan/laravel-llpay``
2. 发布配置文件
``php artisan vendor:publish --provider="Ethan\LianLianPay\LianLianPayServiceProvider"``
3. 修改配置文件``config/lianlianpay.php ``
4. 使用方式
   1. 连连付款类，需要另外开通，[查看连连开放平台文档](https://open.lianlianpay.com/docs/send-money/instant/overview.html)
    ```php
    LianLianPay::InstantPay();
    ```
   
   2. 连连收款类 [查看连连开放平台文档](https://open.lianlianpay.com/apis/bankcardprepay.html)
   ```php
   LianLianPay::Pay();
   ```
   
   3. 连连退款类 [查看连连开放平台文档](https://open.lianlianpay.com/apis/refund.html)
   ```php
   LianLianPay::Refund();
   ```
   
   4. 连连对账单类，需要另外开通，[查看连连开放平台文档](https://open.lianlianpay.com/docs/development/report-sftp.html)
   ```php
   LianLianPay::Reconciliation();
   //filesystems里的disk配置要配置连连对账单sftp服务器信息
   //需要安装laravel的sftp插件
    'llpay' => [
            'driver' => 'sftp',
            'host' => 'dev.lianlianpay.com',
            'username' => '20170328000044444',
            'password' => '123456',
            'port' => 9122,
            'root' => '/20170328000044444',
        ],
   ```
   4. 具体使用方式敬请期待[wiki](https://github.com/ethanfly/laravel-llpay/wiki)



[连连开放平台文档](https://open.lianlianpay.com/apis/get-started.html)
