<?php

namespace MarkdownPublisher\WordPress\Repository;

use MarkdownPublisher\WordPress\Proxy;

abstract class Repository
{
    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @param Proxy $proxy
     */
    public function __construct(Proxy $proxy)
    {
        $this->setProxy($proxy);
    }

    /**
     * @param Proxy $proxy
     * @return $this;
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return Proxy
     * @throws \Exception if not set
     */
    public function getProxy()
    {
        if (!$this->proxy) {
            throw new \Exception("Proxy not set");
        }
        return $this->proxy;
    }


} 