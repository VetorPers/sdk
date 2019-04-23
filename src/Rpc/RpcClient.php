<?php

namespace Sdk\Rpc;

class RpcClient
{
    private $serviceName;

    /**
     * @var array
     */
    private static $services;

    /**
     * todo 重试机制
     * RpcClient constructor.
     *
     * @param array $services
     */
    public function __construct($serviceName, $timeout = 500, $retry = 3)
    {
        $this->serviceName = $serviceName;

        if (isset(self::$services[$serviceName])) return $this;

        try {
            $service = Dispatcher::getService($serviceName);
            $client = new \swoole_client(SWOOLE_SOCK_TCP);
            $client->connect($service['ip'], $service['port'], 0.5);
            self::$services[$serviceName] = $client;
        } catch (\Exception $e) {
            $retry--;
        }
    }


    public static function getService()
    {

    }

    public function pack($request)
    {
//        var_dump($request);
        $msg = json_encode($request, true);

//        var_dump($msg);
        return pack('N', strlen($msg)) . $msg;
    }

    public function unpack($data)
    {

    }

    public function __call($name, $arguments)
    {
        $client = self::$services[$this->serviceName];
        $request = new Request();
        $request->setService($this->serviceName);
        $request->setAction($name);
        $request->setParameters($arguments);

        $client->send($this->pack($request));

        $reponse = $client->recv();

        $body = substr($reponse, 4);

        return json_decode($body, true);
    }
}

