<?php

namespace Ethan\LianLianPay\Services;

use Ethan\LianLianPay\Services\traits\BasePayTrait;
use Illuminate\Support\Facades\Storage;

class Reconciliation
{
    use BasePayTrait;

    /**
     * 获取文件信息
     * @param string $file_name
     * @return string|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author by ethan at 2022/1/24 17:34
     */
    private function getFile(string $file_name)
    {
        $disk = $this->config['disk'];
        $disk = Storage::disk($disk);
        return $disk->exists($file_name) ? $disk->get($file_name) : null;
    }

    /**
     * 获取对账单明细
     * @param string $type JYMX收款明细,FKMX付款明细,JYMXSUM收款汇总,FKMXSUM付款汇总,RHZZW日汇总账务,YHZZW月汇总账务
     * @param string $date 日账单YYYYMMDD,月账单YYYYMM
     * @return string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author by ethan at 2022/1/24 17:29
     */
    public function getDetail(string $type = 'JYMX', string $date = '')
    {
        $oid = $this->config['oid_partner'] ?? '';
        $file_name = $type . '_' . $oid . '_' . $date . '.txt';

        $file = $this->getFile($file_name);
        $keys = [
            "shddh", "shh", "shddsj", "shywbh", "ytddh", "ytzwrq", "ddje",
            "shskbz", "jyzt", "gxsj", "sxf", "zfcp", "zffs", "ddxx", "skfyh",
            "skfzh", "skfmc"
        ];
        if ($file !== null) {
            $data = explode("\n", $file);
            $result = [];
            foreach ($data as $k => $datum) {
                if (strlen($datum) > 0 && $k > 0) {
                    $temp = [];
                    $columns = explode(",", $datum);
                    foreach ($keys as $i => $key) {
                        $temp[$key] = $columns[$i] ?? '';
                    }
                    $result[] = $temp;
                }
            }
            return $result;
        } else {
            return [];
        }
    }
}
