<?php

namespace Sdk\Rpc;

class Dispatcher
{
    public static $services;

    /**
     * 更新配置
     *
     * @param $list
     */
    public static function configUpdate($list)
    {
        var_dump($list);
        // todo
    }

    public static function getService($service)
    {
        //todo 首先从本地取
        $sql = "select * from services where `name`=?";
        $list = \SqlExecute::getAll($sql, [$service]);

        // todo 选举算法
        return array_pop($list);
    }

    private static function loadBalance($service)
    {

    }
}
