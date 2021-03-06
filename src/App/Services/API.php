<?php

namespace Raftx24\Helper\App\Services;

use Curl\Curl;
use Exception;
use Illuminate\Support\Str;
use Raftx24\Helper\App\Helpers\StorageHelper;

class API
{
    protected $logFolder = null;
    protected $baseUrl = null;
    protected $curl = null;

    protected function request($path, $parameters, $method = 'post', $json = true, $log = false)
    {
        $this->initCurl($path, $parameters, $json);

        try {
            $res = $this->curl->{$method}($this->url($path), $parameters);
        } catch (Exception $exception) {
            $res = $exception->getMessage();
        }

        if ($log) {
            $this->log($path, $parameters, $res);
        }

        return $res;
    }

    protected function log($path, $parameters, $res)
    {
        StorageHelper::createStorageFolder($this->logFolder);

        file_put_contents(
            $this->filePath($path),
            $this->content($path, $parameters, $res)
        );
    }

    protected function filePath(string $path): string
    {
        $name = collect(explode(DIRECTORY_SEPARATOR, $path))->last();

        $fileName = $this->logFolder.DIRECTORY_SEPARATOR
            .date('Y-m-d H:i:s').'.'
            .$name.'.txt';

        return storage_path($fileName);
    }

    protected function content(string $path, $parameters, $res): string
    {
        return $this->url($path).PHP_EOL
            .$path.'?'.print_r($parameters, true).PHP_EOL
            .json_encode($parameters).PHP_EOL
            .json_encode($res, JSON_PRETTY_PRINT).PHP_EOL;
    }

    protected function initCurl($path, $parameters, $json): Curl
    {
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);

        if ($json) {
            $this->curl->setHeader('Content-Type', 'application/json');
        }

        $this->curl->setDefaultJsonDecoder(true);

        return $this->curl;
    }

    protected function url($path): string
    {
        return Str::startsWith(strtolower($path), ['http://'])
            ? $path
            : rtrim($this->baseUrl, '/').'/'.$path;
    }
}
