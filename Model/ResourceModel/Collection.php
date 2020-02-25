<?php

/**
 * Copyright (c) 2020 Jonathan Martz
 */

namespace JonathanMartz\WebApiManager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'JonathanMartz\WebApiManager\Model\Request',
            'JonathanMartz\WebApiManager\Model\ResourceModel\Resource'
        );
    }
}
