<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Bluerhinos\phpMQTT;
require_once APPPATH . 'third_party/phpmqtt/autoload.php';

class Mqtt
{
    private $client;

    public function __construct()
    {
        $CI =& get_instance();
        $CI->config->load('mqtt');
        $config = $CI->config->item('mqtt');
        $this->client = new phpMQTT(
            $config['host'],
            $config['port'],
            $config['client_id']
        );
    }

    public function publish($topic, $payload, $qos = 0)
    {
        $CI =& get_instance();
        $config = $CI->config->item('mqtt');

        if (!$this->client->connect(
            true,
            null,
            $config['username'],
            $config['password']
        )) {
            return false;
        }

        $this->client->publish(
            $topic,
            json_encode($payload),
            $qos
        );

        $this->client->close();

        return true;
    }
}