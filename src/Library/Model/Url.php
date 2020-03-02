<?php

namespace AA\Library\Model;

class Url
{
    /**
     * @var string|string
     */
    protected $url;

    /**
     * @var mixed
     */
    protected $urlInfo;

    /**
     * Url constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->urlInfo = parse_url($url);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function isSameHost(string $url)
    {
        return (strpos($url, $this->getHost()) !== false) ? true : false;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return str_replace('www.', '', strtolower($this->urlInfo['host']));
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return strtolower($this->urlInfo['scheme']);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return strtolower($this->urlInfo['path']);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->getPath();
    }

    /**
     * @return string|string
     */
    public function getOriginalUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getUrl();
    }

}