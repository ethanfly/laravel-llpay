<?php

namespace Ethan\LianLianPay\Services\traits;

use Illuminate\Config\Repository;

trait BasePayTrait
{
    use LLPayCore, LLPayMd5, LLPayRsa, LLPaySecurity;

    protected $config;

    protected $env;

    /**
     *连连退款网关地址
     */

    public function __construct(array $config, string $env = 'dev')
    {
        $this->config = $config;
        $this->env = $env;
    }

    /**
     * 生成签名结果
     * @param array $para_sort 已排序要签名的数组
     * @return string 签名结果字符串
     */
    protected function buildRequestMysign(array $para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        if ($this->config['debug']) {
            \Log::info($prestr);
        }
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
    protected function buildRequestPara(array $para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        $para_sort['sign_type'] = strtoupper(trim($this->config['sign_type']));
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        foreach ($para_sort as $key => $value) {
            $para_sort[$key] = $value;
        }
        if ($this->config['debug']) {
            \Log::info($para_sort);
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
    public function buildRequestJSON(array $para_temp, string $llpay_gateway_new, bool $build_para = true)
    {

        if ($build_para) {
            //待请求参数数组字符串
            $request_data = $this->buildRequestPara($para_temp);
        } else {
            $request_data = $para_temp;
        }
        if ($this->config['debug']) {
            \Log::info($request_data);
        }
        //远程获取数据
        $sResult = $this->getHttpResponseJSON($llpay_gateway_new, $request_data);

        return $this->response($sResult);
    }

    /**
     * 统一返回格式封装
     * @param string $result
     * @return mixed
     * @author by ethan at 2022/1/24 14:24
     */
    protected function response(string $result)
    {
        if ($this->config['debug']) {
            \Log::info($result);
        }
        return json_decode($result, true);
    }
}
