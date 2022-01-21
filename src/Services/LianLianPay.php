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

use Ethan\LianLianPay\Services\traits\BasePayTrait;
use Illuminate\Config\Repository;

require_once("./lib/llpay_core.function.php");
require_once("./lib/llpay_md5.function.php");
require_once("./lib/llpay_rsa.function.php");
require_once("./lib/llpay_security.function.php");

class LianLianPay
{
    protected $config;

    public function __construct(Repository $config)
    {
        $default = $config->get('default', 'dev');
        $this->config = $config->get($default);
    }

    /**
     * 付款类
     * @return InstantPay
     * @author by ethan at 2022/1/21 17:45
     */
    public function InstantPay()
    {
        return new InstantPay($this->config);
    }

    /**
     * 收款类
     * @return Pay
     * @author by ethan at 2022/1/21 17:47
     */
    public function Pay()
    {
        return new Pay($this->config);
    }

    /**
     * 对账类
     * @return Reconciliation
     * @author by ethan at 2022/1/21 17:47
     */
    public function Reconciliation()
    {
        return new Reconciliation($this->config);
    }


}

?>
