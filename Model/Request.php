<?php
namespace JonathanMartz\WebApiManager\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Request
 * @package JonathanMartz\WebApiManager\Model
 */
class Banned extends AbstractModel
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init("JonathanMartz\WebApiManager\Model\ResourceModel\Banned");
    }
}

?>
