<?php

/**
 * Copyright (c) 2020 Jonathan Martz
 */

namespace JonathanMartz\WebApiManager\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Request
 * @package JonathanMartz\WebApiManager\Model
 */
class Request extends AbstractModel
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init("JonathanMartz\WebApiManager\Model\ResourceModel\Resource");
    }
}

?>
