<?php

namespace Ethan\LianLianPay\Services;

use Ethan\LianLianPay\Services\traits\BasePayTrait;

class Refund
{
    use BasePayTrait;

    /**
     * 退款API
     * @param string $no_refund 商户退款流水号。
     * @param string $dt_refund 商户退款时间。格式为 yyyyMMddHHmmss
     * @param string $money_refund 退款请求中money_refund对应的此次退款的金额，单位为元，精确到小数点后两位，小数点计入字符长度。
     * @param string $notify_url 接收异步通知的线上地址。
     * @param array $options 其余可选扩展字段（参考文档）
     * @return bool|string
     * @author ethan at 2022/7/27 16:56
     */
    public function refund(string $no_refund, string $dt_refund, string $money_refund, string $notify_url, array $options = [])
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://traderapi.lianlianpay.com/refund.htm';
        //测试
        else
            $url = '';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "no_refund" => $no_refund,
            "dt_refund" => $dt_refund,
            'money_refund' => $money_refund,
            'notify_url' => $notify_url,
        );
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        //建立请求
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }

    /**
     * 退款订单查询API
     * @param array $options 其余可选扩展字段（参考文档）
     * @return bool|string
     * @author ethan at 2022/7/27 16:58
     */
    public function refundQuery(array $options = [])
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://queryapi.lianlianpay.com/refundquery.htm';
        //测试
        else
            $url = '';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
        );
        //建立请求
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }
}
