<?php
/**
 * Created by PhpStorm.
 * User: adamyu
 * Date: 2018/1/15
 * Time: 16:13
 */

namespace adamyu\helper\crypt;


use yii\base\InvalidParamException;

abstract class Crypt
{

    public $key;

    abstract public function encrypt(array $data);

    /**
     * @param array $data
     * @return string
     */
    protected function preData(array $data):string
    {
        if (empty($data)) {
            throw new InvalidParamException('加密数据不能为空');
        }
        $data_list = $this->enResponseData($data);
        return strtoupper(md5($this->key.$data_list.$this->key));
    }

    /**
     * @param $response
     * @return string
     */
    protected function enResponseData(array $response):string 
    {
        if (isset($response['sign'])) {
            unset($response['sign']);
        }

        ksort($response);
        return $this->formatBizQueryParaMap($response);
    }

    /**
     * @param $paraMap
     * @param bool $urlEncode
     * @return string
     */
    protected function formatBizQueryParaMap(array $paraMap, bool $urlEncode = false):string
    {
        $buff = "";
        foreach ($paraMap as $k => $v) {
            if($urlEncode){
                $v = urlencode($v);
            }
            $buff .= $k .$v;
        }
        $reqPar="";
        if (strlen($buff) > 0) {
            $reqPar = $buff;
        }
        return $reqPar;
    }

}