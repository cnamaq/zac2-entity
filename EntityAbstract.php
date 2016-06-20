<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;

abstract class EntityAbstract
{

    /**
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        if (!is_null($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            try {
                $method = $this->getMethodName($key);
                $this->$method($value);
            } catch (\DomainException $e) {
                echo 'protected $'.$key.';<br>';
                $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('dic');
                $logger = $container->get('logger');
                $logger->debug($e->getMessage());
            }
        }

        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    protected function getMethodName($key)
    {
        $tab = explode('_', $key);
        foreach ($tab as $ind => $member) {
            $tab[$ind] = ucfirst($member);
        }
        $method = 'set' . implode('', $tab);

        if (!method_exists($this, $method)) {
            throw new \DomainException('méthode inconnue dans la classe ' . get_class($this) . ' : ' . $key);
        }

        return $method;
    }

}
