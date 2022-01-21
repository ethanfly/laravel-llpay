<?php

namespace Ethan\LianLianPay\Services;

/* *
 * 类名：LLpaySubmit
 * 功能：连连支付接口请求提交类
 * 详细：构造连连支付各接口表单HTML文本，获取远程HTTP数据
 * 版本：1.1
 * 日期：2014-04-16
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

use Illuminate\Config\Repository;

require_once("./lib/llpay_core.function.php");
require_once("./lib/llpay_md5.function.php");
require_once("./lib/llpay_rsa.function.php");
require_once("./lib/llpay_security.function.php");

class LianLianPay
{

    protected $config;

    /**
     *连连退款网关地址
     */

    function __construct(Repository $config)
    {
        $default = $config->get('default', 'dev');
        $this->config = $config->get($default);
    }

    /**
     * 生成签名结果
     * @param array $para_sort 已排序要签名的数组
     * @return string 签名结果字符串
     */
    public function buildRequestMysign(array $para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);
        $mysign = "";
        switch (strtoupper(trim($this->config['sign_type']))) {
            case "MD5" :
                $mysign = md5Sign($prestr, $this->config['key']);
                break;
            case "RSA" :
                $mysign = RsaSign($prestr, $this->config['RSA_PRIVATE_KEY']);
                break;
            default :
                $mysign = "";
        }
        return $mysign;
    }

    /**
     * 生成要请求给连连支付的参数数组
     * @param array $para_temp 请求前的参数数组
     * @return array 要请求的参数数组
     */
    public function buildRequestPara(array $para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->config['sign_type']));
        foreach ($para_sort as $key => $value) {
            $para_sort[$key] = $value;
        }
        return $para_sort;
        //return urldecode(json_encode($para_sort));
    }


    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果
     * @param array $para_temp 请求参数数组
     * @param string $llpay_gateway_new 请求url
     * @return bool|string 连连支付处理结果
     */
    public function buildRequestJSON(array $para_temp, string $llpay_gateway_new)
    {
        $sResult = '';

        //待请求参数数组字符串
        $request_data = $this->buildRequestPara($para_temp);

        //远程获取数据
        $sResult = getHttpResponseJSON($llpay_gateway_new, $request_data);

        return $sResult;
    }

    /**
     * 付款
     * @param string $no_order 商户付款流水号
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
    public function instantPay(string $no_order, float $money,string $acct_name,string $card_no,string $info_order,int $flag_card,string $notify_url,string $platform)
    {
        /**************************请求参数**************************/
        //商户时间
        $dt_order = date('YmdHis');
        //金额
        $money_order = $money;
        //版本号
        $api_version = '1.0';
        //实时付款交易接口地址
        $llpay_payment_url = 'https://instantpay.lianlianpay.com/paymentapi/payment.htm';
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "sign_type" => trim($this->config['sign_type']),
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
        //建立请求
        $sortPara = $this->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array(
            "oid_partner" => trim($this->config['oid_partner']),
            "pay_load" => ll_encrypt($json, $this->config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $this->buildRequestJSON($parameterRequest, $llpay_payment_url);
        //调用付款申请接口，同步返回0000，是指创建连连支付单成功，订单处于付款处理中状态，最终的付款状态由异步通知告知
        //出现1002，2005，4006，4007，4009，9999这6个返回码时或者没返回码，抛exception（或者对除了0000之后的code都查询一遍查询接口）调用付款结果查询接口，明确订单状态，不能私自设置订单为失败状态，以免造成这笔订单在连连付款成功了，而商户设置为失败,用户重新发起付款请求,造成重复付款，商户资金损失
        //对连连响应报文内容需要用连连公钥验签
        return $html_text;
    }
}

?>
