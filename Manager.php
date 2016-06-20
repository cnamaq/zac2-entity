<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;

use Monolog\Logger;
use Zac2\Data\Client\ReaderInterface;
use Zac2\Data\Parser\ParserInterface;
use Zac2\Data\Request\Adapter;
use Zac2\Filter\Multi\Multi;

class Manager
{
    /**
     * @var Adapter
     */
    protected $dataRequestAdapter;
    /**
     * @var ReaderInterface
     */
    protected $dataClient;
    /**
     * @var ParserInterface
     */
    protected $parser;
    /**
     * @var mixed
     */
    protected $cache;
    /**
     * @var Builder
     */
    protected $builder;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Manager constructor.
     * @param Adapter $dataRequestAdapter
     * @param ReaderInterface $dataClient
     * @param ParserInterface $parser
     * @param $cache
     * @param Builder $builder
     * @param Logger|null $logger
     */
    public function __construct(
        Adapter $dataRequestAdapter,
        ReaderInterface $dataClient,
        ParserInterface $parser,
        $cache,
        Builder $builder,
        Logger $logger = null
    ) {
        $this->dataRequestAdapter = $dataRequestAdapter;
        $this->dataClient = $dataClient;
        $this->parser = $parser;
        $this->cache = $cache;
        $this->builder = $builder;
        $this->logger = $logger;
    }

    /**
     * @param string $entity
     * @param Multi|null $filterMulti
     * @return EntityAbstract[]
     */
    public function get($entity, Multi $filterMulti = null)
    {
        $id = $this->getCacheId($entity . 'objet', $filterMulti);
        if (!$this->getCache()->contains($id)) {
            $arrayData = $this->getArrayData($entity, $filterMulti);
            $result    = $this->getBuilder()->getEntity($entity, $arrayData);
            $this->getCache()->save($id, $result);
        }

        return $this->getCache()->fetch($id);
    }

    /**
     * @param  string $entity
     * @param  Multi|null $filterMulti
     * @return mixed
     */
    public function getArrayData($entity, Multi $filterMulti = null)
    {
        $id = $this->getCacheId($entity . 'data', $filterMulti);
        if (!$this->getCache()->contains($id)) {
            $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('dic');
            $logger = $container->get('logger');
            if (!is_null($filterMulti)) {
                $dataRequestAdapter = $filterMulti->filter($this->getDataRequestAdapter());
            } else {
                $dataRequestAdapter = $this->getDataRequestAdapter();
            }
            $logger->debug($dataRequestAdapter->getRequest());
            $rawData   = $this->getDataClient()->read($dataRequestAdapter);
            $arrayData = $this->getParser()->parse($rawData);
            $this->getCache()->save($id, $arrayData);
        }

        return $this->getCache()->fetch($id);
    }

    /**
     * @param string $entity
     * @param int $id
     * @return EntityAbstract
     */
    public function getObject($entity, $id) {

    }

    public function getCollection()
    {

    }

    /**
     * @param string $entity
     * @param Multi|null $filterMulti
     * @return string
     */
    protected function getCacheId($entity, Multi $filterMulti = null)
    {
        return (is_null($filterMulti)) ? $entity : $entity . serialize($filterMulti);
    }

    /**
     * @return Adapter
     */
    public function getDataRequestAdapter()
    {
        return $this->dataRequestAdapter;
    }

    /**
     * @param Adapter $dataRequestAdapter
     */
    public function setDataRequestAdapter($dataRequestAdapter)
    {
        $this->dataRequestAdapter = $dataRequestAdapter;
    }

    /**
     * @return ReaderInterface
     */
    public function getDataClient()
    {
        return $this->dataClient;
    }

    /**
     * @param ReaderInterface $dataClient
     */
    public function setDataClient($dataClient)
    {
        $this->dataClient = $dataClient;
    }

    /**
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param ParserInterface $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param mixed $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param Builder $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

}
