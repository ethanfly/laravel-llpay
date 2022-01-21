<?php

namespace Ethan\LianLianPay\Facades;

use Ethan\LianLianPay\Services\InstantPay;
use Ethan\LianLianPay\Services\Pay;
use Illuminate\Support\Facades\Facade;

/**
 * @method static InstantPay InstantPay() 连连付款类
 * @method static Pay Pay() 连连收款类
 * @see \Ethan\LianLianPay\Services\LianLianPay
 */
class LianLianPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lianlianpay';
    }
}
