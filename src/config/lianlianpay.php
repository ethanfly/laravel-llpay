<?php
return [
    /* *
    * 配置文件
    * 版本：1.0
    * 日期：2016-11-28
    * 说明：
    * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
    */
    'default' => 'dev',
    //多个环境可以切换
    'dev' => [
        [
            //商户编号是商户在连连钱包支付平台上开设的商户号码，为18位数字，如：201306081000001016
            'oid_partner' => '',
            //秘钥格式注意不能修改（左对齐，右边有回车符）  商户私钥，通过openssl工具生成,私钥需要商户自己生成替换，对应的公钥通过商户站上传
            'RSA_PRIVATE_KEY' => <<< RSA_PRIVATE_KEY
-----BEGIN RSA PRIVATE KEY-----

-----END RSA PRIVATE KEY-----
RSA_PRIVATE_KEY,
            //连连银通公钥
            'LIANLIAN_PUBLICK_KEY' => <<< LIANLIAN_PUBLICK_KEY
-----BEGIN PUBLIC KEY-----

-----END PUBLIC KEY-----
LIANLIAN_PUBLICK_KEY,
            //安全检验码，以数字和字母组成的字符
            'key' => '',
            //签名方式 不需修改
            'sign_type' => strtoupper('RSA'),
            //字符编码格式 目前支持 gbk 或 utf-8
            'input_charset' => strtolower('utf-8')
        ]
    ],
    'production' => [
        [
            //商户编号是商户在连连钱包支付平台上开设的商户号码，为18位数字，如：201306081000001016
            'oid_partner' => '',
            //秘钥格式注意不能修改（左对齐，右边有回车符）  商户私钥，通过openssl工具生成,私钥需要商户自己生成替换，对应的公钥通过商户站上传
            'RSA_PRIVATE_KEY' => <<< RSA_PRIVATE_KEY
-----BEGIN RSA PRIVATE KEY-----

-----END RSA PRIVATE KEY-----
RSA_PRIVATE_KEY,
            //连连银通公钥
            'LIANLIAN_PUBLICK_KEY' => <<< LIANLIAN_PUBLICK_KEY
-----BEGIN PUBLIC KEY-----

-----END PUBLIC KEY-----
LIANLIAN_PUBLICK_KEY,
            //安全检验码，以数字和字母组成的字符
            'key' => '',
            //签名方式 不需修改
            'sign_type' => strtoupper('RSA'),
            //字符编码格式 目前支持 gbk 或 utf-8
            'input_charset' => strtolower('utf-8')
        ]
    ]
];
?>
