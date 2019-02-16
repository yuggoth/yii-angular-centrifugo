<?php
/**
 * Created by IntelliJ IDEA.
 * User: sl4mmer
 * Date: 07.10.14
 * Time: 15:26
 */

namespace app\controllers\phpcent;

interface ITransport
{

    /**
     * @param $host
     * @param $data
     * @return mixed
     */
    public function communicate($host, $data);

} 