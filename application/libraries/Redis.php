<?php
//sudo service redis-server stop

class Redis
{

    function config()
    {
        // Parameters passed using a named array:
        $client = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
            'database' => 1
        ]);

        // Same set of parameters, passed using an URI string:
        // $client = new Predis\Client('tcp://10.0.0.1:6379');
        return $client;
    }
}
