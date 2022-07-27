<?php

namespace Ethan\LianLianPay\Facades;

use Ethan\LianLianPay\Services\InstantPay;
use Ethan\LianLianPay\Services\Pay;
use Ethan\LianLianPay\Services\Reconciliation;
use Ethan\LianLianPay\Services\Refund;
use Illuminate\Support\Facades\Facade;

/**
 * @method static InstantPay InstantPay(?string $default_config = null) 连连付款类
 * @method static Pay Pay(?string $default_config = null) 连连收款类
 * @method static Reconciliation Reconciliation(?string $default_config = null) 连连对账单类
 * @method static Refund Refund(?string $default_config = null) 连连退款类
 * @see \Ethan\LianLianPay\Services\LianLianPay
 */
class LianLianPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lianlianpay';
    }
}
