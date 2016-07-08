<?php

/**
 * Description of GoogleGeocoder
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */

namespace Alexpechkarev\GoogleGeocoder;


class GoogleGeocoder {

    /*
    |--------------------------------------------------------------------------
    | Application Key
    |--------------------------------------------------------------------------
    |
    | Your application's API key. This key identifies your application for
    | purposes of quota management. Learn how to get a key from the APIs Console.
    */
    protected $applicationKey;


    /*
    |--------------------------------------------------------------------------
    | Request Url
    |--------------------------------------------------------------------------
    |
    */
    protected $requestUrl;

    /*
    |--------------------------------------------------------------------------
    | Requested Format
    |--------------------------------------------------------------------------
    |
    */
    protected $format;

    /*
    |--------------------------------------------------------------------------
    | Geocoding request parameters
    |--------------------------------------------------------------------------
    |
    */
    protected $param;



    /**
     * Set Application Key and Request URL
     *
     * @param string $format - output format json or xml
     * @param array $param - geocoding request parameters
     */
    public function __construct($config)
    {
        $this->applicationKey   = $config['applicationKey'];
        $this->requestUrl       = $config['requestUrl'];
    }


    /**
     * Get json from url
     * @return string
     * @throws \RuntimeException
     */
    protected function call()
    {
        $json = file_get_contents($this->requestUrl[$this->format].$this->param);
        $obj = json_decode($json);

        if (!isset($obj->results[0])) {
            throw new \RuntimeException('Can\'t get geolocation.');
        }

        return $obj->results[0];
    }


    /**
     * Geocoding request
     *
     * @param string $format - output format json or xml
     * @param array $param - geocoding request parameters
     *
     * @return string
     */
    public function geocode($format, $param)
    {
        $this->format     = array_key_exists($format, $this->requestUrl) ? $format : 'json';
        $param['key']     = $this->applicationKey;
        $this->param      = http_build_query($param);

        return $this->call();
    }

}
