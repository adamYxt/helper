<?php
/**
 * Created by PhpStorm.
 * User: adamyu
 * Date: 2018/1/15
 * Time: 16:16
 */

namespace adamyxt\helper\crypt;


class Encrypt extends Crypt
{

    /**
     * @param array $data
     * @return string
     */
    public function encrypt(array $data):string
    {
        return $this->preData($data);
    }

}