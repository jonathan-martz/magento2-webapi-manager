<?php

namespace JonathanMartz\WebApiManager\Model\ResourceModel\Banned;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'JonathanMartz\WebApiManager\Model\Banned',
            'JonathanMartz\WebApiManager\Model\ResourceModel\Banned'
        );
    }
}
