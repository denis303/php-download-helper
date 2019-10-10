<?php

namespace denis303\php;

use Exception;

class DownloadHelper
{

    public static function get($url, array $options = [], $throwExceptions = true, &$error = null)
    {
        $ch = curl_init($url);

        $opt_array = [
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 5.1; rv: 23.0) Gecko/20100101 Firefox/23.0",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];

        foreach($options as $key => $value)
        {
            $opt_array[$key] = $value;
        }

        curl_setopt_array($ch, $opt_array);

        $result = curl_exec($ch);

        if ($result === false)
        {
            $error = curl_error($ch);
        }

        curl_close($ch);

        if ($throwExceptions && ($result === false))
        {
            throw new Exception($error);
        }

        return $result;
    }

    public static function toFile($url, $filename, array $options = [], $throwExceptions = true, &$error = null)
    {
        $fp = fopen($filename, "w");

        if ($fp === false)
        {
            $error = 'Can\'t open file: ' . $filename;

            if ($throwExceptions)
            {
                throw new Exception($error);
            }

            return false;
        }

        $options[CURLOPT_FILE] = $fp;

        $result = static::get($url, $options, false, $error);

        if (fclose($fp) === false)
        {
            $error = 'Can\'t close file: ' . $filename;

            if ($throwExceptions)
            {
                throw new Exception($error);
            }

            return false;
        }

        if ($throwExceptions && ($result === false))
        {
            throw new Exception($error);
        }

        return $result;
    }

}    