<?php

namespace Ethan\LianLianPay\Services\traits;

use Illuminate\Config\Repository;

trait BasePayTrait
{
    use LLPayCore, LLPayMd5, LLPayRsa, LLPaySecurity;

    protected $config;

    /**
     *连连退款网关地址
     */

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 生成签名结果
     * @param array $para_sort 已排序要签名的数组
     * @return string 签名结果字符串
     */
    public function buildRequestMysign(array $para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        $mysign = "";
        switch (strtoupper(trim($this->config['sign_type']))) {
            case "MD5" :
                $mysign = $this->md5Sign($prestr, $this->config['key']);
                break;
            case "RSA" :
                $mysign = $this->RsaSign($prestr, $this->config['RSA_PRIVATE_KEY']);
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
        $para_filter = $this->paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);
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
        $sResult = $this->getHttpResponseJSON($llpay_gateway_new, $request_data);

        return $sResult;
    }
}
