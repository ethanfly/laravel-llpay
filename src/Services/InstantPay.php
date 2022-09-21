<?php

namespace Ethan\LianLianPay\Services;

use Ethan\LianLianPay\Services\traits\BasePayTrait;

class InstantPay
{
    use BasePayTrait;

    /**
     * 付款
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param float $money 金额(元)
     * @param string $acct_name 收款人姓名
     * @param string $card_no 银行账号
     * @param string $info_order 订单描述
     * @param int $flag_card 对私标记
     * @param string $notify_url 服务器异步通知地址
     * @param string $platform 平台来源
     * @return bool|string
     * @author by ethan at 2022/1/21 16:16
     */
    public function instantPay(string $no_order, float $money, string $acct_name, string $card_no, string $info_order, int $flag_card, string $bank_code, string $notify_url, string $platform = '', array $options = [])
    {
        /**************************请求参数**************************/
        //商户时间
        $dt_order = date('YmdHis');
        //金额
        $money_order = $money;
        //版本号
        $api_version = '1.0';
        //实时付款交易接口地址(正式)
        if ($this->env == 'production')
            $url = 'https://instantpay.lianlianpay.com/paymentapi/payment.htm';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/paymentapi/payment.htm';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "no_order" => $no_order,
            "dt_order" => $dt_order,
            "money_order" => $money_order,
            "acct_name" => $acct_name,
            "card_no" => $card_no,
            "info_order" => $info_order,
            "flag_card" => $flag_card,
            "notify_url" => $notify_url,
            "platform" => $platform,
            "api_version" => $api_version
        );
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        if ($flag_card == 1)
            $parameter['bank_code'] = $bank_code;
        //建立请求
        $sortPara = $this->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "pay_load" => $this->ll_encrypt($json, $this->config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $this->buildRequestJSON($parameterRequest, $url);
        //调用付款申请接口，同步返回0000，是指创建连连支付单成功，订单处于付款处理中状态，最终的付款状态由异步通知告知
        //出现1002，2005，4006，4007，4009，9999这6个返回码时或者没返回码，抛exception（或者对除了0000之后的code都查询一遍查询接口）调用付款结果查询接口，明确订单状态，不能私自设置订单为失败状态，以免造成这笔订单在连连付款成功了，而商户设置为失败,用户重新发起付款请求,造成重复付款，商户资金损失
        //对连连响应报文内容需要用连连公钥验签
        return $html_text;
    }

    /**
     * 确认付款
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param string $confirm_code 付款申请API返回的确认码。
     * @param string $notify_url 服务器异步通知地址
     * @param string $platform 平台来源
     * @return bool|string
     * @author by ethan at 2022/1/21 17:30
     */
    public function instantPayConfirm(string $no_order, string $confirm_code, string $notify_url, string $platform = '')
    {
        /**************************请求参数**************************/
        //版本号
        $api_version = '1.0';
        //确认付款接口地址正式
        if ($this->env == 'production')
            $url = 'https://instantpay.lianlianpay.com/paymentapi/confirmPayment.htm';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/paymentapi/confirmPayment.htm';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "no_order" => $no_order,
            "confirm_code" => $confirm_code,
            "notify_url" => $notify_url,
            "platform" => $platform,
            "api_version" => $api_version
        );
        //建立请求
        $sortPara = $this->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "pay_load" => $this->ll_encrypt($json, $this->config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $this->buildRequestJSON($parameterRequest, $url);
        //调用付款申请接口，同步返回0000，是指创建连连支付单成功，订单处于付款处理中状态，最终的付款状态由异步通知告知
        //出现1002，2005，4006，4007，4009，9999这6个返回码时或者没返回码，抛exception（或者对除了0000之后的code都查询一遍查询接口）调用付款结果查询接口，明确订单状态，不能私自设置订单为失败状态，以免造成这笔订单在连连付款成功了，而商户设置为失败,用户重新发起付款请求,造成重复付款，商户资金损失
        //对连连响应报文内容需要用连连公钥验签
        return $html_text;
    }

    /**
     * 付款结果查询
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param string $platform 平台来源
     * @return bool|string
     * @author by ethan at 2022/1/21 17:34
     */
    public function instantPayQuery(string $no_order, string $platform = '')
    {
        /**************************请求参数**************************/
        //版本号
        $api_version = '1.0';
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://instantpay.lianlianpay.com/paymentapi/queryPayment.htm';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/paymentapi/queryPayment.htm';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "no_order" => $no_order,
            "platform" => $platform,
            "api_version" => $api_version
        );
        //建立请求
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }
}
