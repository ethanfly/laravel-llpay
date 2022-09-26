<?php

namespace Ethan\LianLianPay\Services;

use Ethan\LianLianPay\Services\traits\BasePayTrait;

class Pay
{
    use BasePayTrait;

    /**
     * 收银台支付
     * @param array $options
     * @return bool|string
     * @author ethan at 2022/8/16 17:22
     */
    public function payCreateBill(array $options)
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://payserverapi.lianlianpay.com/v1/paycreatebill';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/fourelementapi/v1/paycreatebill';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "api_version" => '1.0',
            'time_stamp' => date('YmdHis'),
            'busi_partner' => '109001',
        );
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        if ($this->config['debug']) {
            \Log::info($parameter);
        }
        //建立请求
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }

    /**
     * 微信支付宝支付创单API
     * @param string $user_id 用户编号。 商户系统内对用户的唯一编码，可以为自定义字符串，加密密文或者邮箱等可以唯一定义用户的标识。
     * @param string $busi_partner 虚拟商品销售：101001。实物商品销售：109001。当busi_partner与您的商户号的业务属性不相符时， 该次请求将返回请求无效。
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param string $dt_order 商户订单时间。格式为yyyyMMddHHmmss
     * @param string $money_order 交易金额。请求no_order对应的订单总金额，单位为元，精确到小数点后两位，小数点计入字符长度。
     * @param string $notify_url 接收异步通知的线上地址。连连支付支付平台在用户支付成功后通知商户服务端的地址。
     * @param string $risk_item 风险控制参数。
     * @param string $pay_type 付款方式。
     * @param array $options 其余可选扩展字段（参考文档）
     * @return bool|string
     * @author ethan at 2022/7/26 20:39
     */
    public function thirdPartyPrepay(string $user_id, string $busi_partner, string $no_order, string $dt_order, string $money_order, string $notify_url, string $risk_item, string $pay_type, array $options = [])
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://mpayapi.lianlianpay.com/v1/bankcardprepay';
        //测试
        else
            $url = '';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "user_id" => $user_id,
            "busi_partner" => $busi_partner,
            'no_order' => $no_order,
            'dt_order' => $dt_order,
            'money_order' => $money_order,
            'notify_url' => $notify_url,
            'risk_item' => $risk_item,
            'pay_type' => $pay_type,
        );
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        if ($this->config['debug']) {
            \Log::info($parameter);
        }
        //建立请求
        $sortPara = $this->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "pay_load" => $this->ll_encrypt($json, $this->config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $this->buildRequestJSON($parameterRequest, $url, build_para: false);
        return $html_text;
    }

    /**
     * 银行卡签约支付申请API
     * @param string $user_id 用户编号。 商户系统内对用户的唯一编码，可以为自定义字符串，加密密文或者邮箱等可以唯一定义用户的标识。
     * @param string $busi_partner 商户编号是商户在连连支付支付平台上开设的商户号码，为18位数字。
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param string $dt_order 商户订单时间。格式为yyyyMMddHHmmss
     * @param string $name_goods 商户商品名称。
     * @param string $money_order 交易金额。请求no_order对应的订单总金额，单位为元，精确到小数点后两位，小数点计入字符长度。
     * @param string $notify_url 接收异步通知的线上地址。
     * @param string $risk_item 风险控制参数。
     * @param string $pay_type 支付方式。 指定使用的支付方式。
     * @param string $card_no 用户银行卡卡号。
     * @param string $acct_name 用户姓名，为用户在银行预留的姓名信息，中文。
     * @param string $bind_mob 用户手机号码。 为用户在银行预留的手机号码。
     * @param string $id_type 证件类型。
     * @param string $id_no 证件号码。
     * @param array $options 其余可选扩展字段（参考文档）
     * @return bool|string
     * @author ethan at 2022/7/27 10:34
     */
    public function bankCardPrepay(string $user_id, string $busi_partner, string $no_order, string $dt_order, string $name_goods, string $money_order, string $notify_url, string $risk_item, string $pay_type, string $card_no, string $acct_name, string $bind_mob, string $id_type, string $id_no, array $options = [])
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://mpayapi.lianlianpay.com/v1/bankcardprepay';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/mpayapi/v1/bankcardprepay';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "user_id" => $user_id,
            "busi_partner" => $busi_partner,
            'no_order' => $no_order,
            'dt_order' => $dt_order,
            'name_goods' => $name_goods,
            'money_order' => $money_order,
            'notify_url' => $notify_url,
            'risk_item' => $risk_item,
            'pay_type' => $pay_type,
            'card_no' => $card_no,
            'acct_name' => $acct_name,
            'bind_mob' => $bind_mob,
            'id_type' => $id_type,
            'id_no' => $id_no,
        );
        if (!empty($options)) {
            $parameter = array_merge($parameter, $options);
        }
        //建立请求
        $sortPara = $this->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "pay_load" => $this->ll_encrypt($json, $this->config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $this->buildRequestJSON($parameterRequest, $url);
        return $html_text;
    }

    /**
     * 银行卡签约支付验证API
     * @param string $token 授权令牌，有效期为30分钟。
     * @param string $no_order 商户订单号。 为商户系统内对订单的唯一编号，保证唯一。
     * @param string $money_order 交易金额。请求no_order对应的订单总金额，单位为元，精确到小数点后两位，小数点计入字符长度。
     * @param string $verify_code 短信验证码。验证银行预留手机号。
     * @return bool|string
     * @author ethan at 2022/7/27 11:42
     */
    public function bankCardPay(string $token, string $no_order, string $money_order, string $verify_code)
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://mpayapi.lianlianpay.com/v1/bankcardpay';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/mpayapi/v1/bankcardpay';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "token" => $token,
            "no_order" => $no_order,
            "money_order" => $money_order,
            'verify_code' => $verify_code
        );
        //建立请求
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }

    /**
     * 收款结果查询API
     * @param string $no_order 原请求中商户订单号。 为商户系统内对订单的唯一编号。
     * @param string $dt_order 商户订单时间。格式为yyyyMMddHHmmss
     * @param string $oid_paybill 连连付款单号。 全局唯一。
     * @return bool|string
     * @author ethan at 2022/7/27 11:45
     */
    public function orderQuery(string $no_order, string $dt_order, string $oid_paybill = '')
    {
        /**************************请求参数**************************/
        //确认付款接口地址
        if ($this->env == 'production')
            $url = 'https://queryapi.lianlianpay.com/orderquery.htm';
        //测试
        else
            $url = 'https://test.lianlianpay-inc.com/queryapi/orderquery.htm';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "no_order" => $no_order,
            "dt_order" => $dt_order,
            "oid_paybill" => $oid_paybill,
            'query_version' => '1.1'
        );
        //建立请求
        $html_text = $this->buildRequestJSON($parameter, $url);
        return $html_text;
    }
}
