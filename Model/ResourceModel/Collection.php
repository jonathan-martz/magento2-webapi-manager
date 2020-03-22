<?php

namespace JonathanMartz\WebApiManager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'JonathanMartz\WebApiManager\Model\RequeBannedst',
            'JonathanMartz\WebApiManager\Model\ResourceModel\Banned'
        );
    }
}
