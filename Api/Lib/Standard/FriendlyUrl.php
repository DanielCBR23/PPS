<?php

namespace Api\Lib\Standard;

use Api\Lib\Current\Input;

class FriendlyUrl
{

    private $urlParams = [];

    public function __construct()
    {
        $this->appendParametersUrl();
    }

    private function appendParametersUrl()
    {
        $endppoint = Input::getInstance()->getEndpoint();
        $params = explode('/', $endppoint);
        $filter = array_filter($params);
        $urlParameters = array_values($filter);
        
        $this->urlParams = $urlParameters;
    }

    public function getParameter(string $index): string
    {
        return isset($this->urlParams[$index]) ? $this->urlParams[$index] : '';
    }

    public function getParameters(): array
    {
        return $this->urlParams;
    }

    public function getCompleteUrl(): string
    {
        return self::transformToUrl($this->urlParams);
    }

    private static function transformToUrl(array $parameters)
    {
        $url = [];
        foreach ($parameters as $parameter) {
            $url[] = self::transformString($parameter);
        }
        return implode('/', $url);
    }

    private static function transformString(string $word): string
    {
        $word = trim($word);
        $word = str_replace('/', '-', $word);
        $word = str_replace(' ', '-', $word);
        $word = str_replace('"', '', $word);
        $word = str_replace('&quot;', '', $word);


        $from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüçÇ #$|?&';
        $to = 'AAAAEEIOOOUUCaaaaeeiooouucC-----e';
        $word = strtr($word, $from, $to);
        $word = preg_replace("/[^a-zA-Z0-9-]/", '', $word);
        $word = preg_replace("/\-\-+/", '-', $word);
        return strtolower($word);
    }
}