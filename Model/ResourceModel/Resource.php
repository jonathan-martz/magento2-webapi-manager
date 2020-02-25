<?php
namespace JonathanMartz\WebApiManager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Resource
 * @package JonathanMartz\WebApiManager\Model\ResourceModel
 */
class Resource extends AbstractDb
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init("webapi_banned", "id");
    }
}

?>
