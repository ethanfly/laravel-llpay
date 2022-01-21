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
   
   2. 连连收款类
   ```php
   LianLianPay::Pay();
   ```
   
   3. 连连对账单类，需要另外开通，[查看连连开放平台文档](https://open.lianlianpay.com/docs/development/report-sftp.html)
   ```php
   LianLianPay::Reconciliation();
   ```



[连连开放平台文档](https://open.lianlianpay.com/apis/get-started.html)
