<?php

namespace Ethan\LianLianPay\Services;

use Ethan\LianLianPay\Services\traits\BasePayTrait;
use Illuminate\Config\Repository;

/* *
 * 类名：LLpaySubmit
 * 功能：连连支付接口请求提交类
 * 详细：构造连连支付各接口表单HTML文本，获取远程HTTP数据
 * 版本：1.1
 * 日期：2014-04-16
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

class LianLianPay
{
    protected $ll_pay_config;

    protected $config;

    protected $env;

    public function __construct(Repository $config)
    {
        $config = $config->get('lianlianpay');
        $this->env = $config['default'] ?? 'dev';
        $this->config = $config[$this->env] ?? [];
        $this->ll_pay_config = $config;
    }

    /**
     * 付款类
     * @return InstantPay
     * @author by ethan at 2022/1/21 17:45
     */
    public function InstantPay(?string $default_config = null)
    {
        $this->setDefaultConfig($default_config);
        return new InstantPay($this->config, $this->env);
    }

    /**
     * 收款类
     * @return Pay
     * @author by ethan at 2022/1/21 17:47
     */
    public function Pay(?string $default_config = null)
    {
        $this->setDefaultConfig($default_config);
        return new Pay($this->config, $this->env);
    }

    /**
     * 退款类
     * @return Refund
     * @author by ethan at 2022/1/21 17:47
     */
    public function Refund(?string $default_config = null)
    {
        $this->setDefaultConfig($default_config);
        return new Refund($this->config, $this->env);
    }

    /**
     * 对账类
     * @return Reconciliation
     * @author by ethan at 2022/1/21 17:47
     */
    public function Reconciliation(?string $default_config = null)
    {
        $this->setDefaultConfig($default_config);
        return new Reconciliation($this->config, $this->env);
    }

    /**
     * 设置默认配置
     * @param string|null $env
     * @author ethan at 2022/7/27 17:06
     */
    private function setDefaultConfig(?string $config_key = null)
    {
        if (!empty($config_key)) {
//            $this->env = $env;
            $this->config = $this->ll_pay_config[$config_key] ?? [];
        }
    }
}
