<?php

namespace Ethan\LianLianPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string buildRequestMysign(array $para_sort)
 * @method static array buildRequestPara(array $para_temp)
 * @method static bool|string buildRequestJSON(array $para_temp, string $llpay_gateway_new)
 * @method static bool|string instantPay(string $no_order, float $money,string $acct_name,string $card_no,string $info_order,int $flag_card,string $notify_url,string $platform)
 * @see \Ethan\LianLianPay\Services\LianLianPay
 */
class LianLianPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lianlianpay';
    }
}
