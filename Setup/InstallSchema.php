<?php
/**
 * Copyright (c) 2020 Jonathan Martz
 */

namespace JonathanMartz\WebApiManager\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * Class InstallSchema
 * @package JonathanMartz\WebApiManager\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var string
     */
    public $table = 'webapi_banned';

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();

        if(!$connection->isTableExists($this->table)) {
            try {
                $table = $connection->newTable($setup->getTable($this->table))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    );

                $table->addColumn(
                    'session',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'session'
                );

                $table->addColumn(
                    'ip',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Ip'
                );

                $table->addColumn(
                    'time',
                    Table::TYPE_INTEGER,
                    30,
                    ['nullable' => false, 'default' => time()],
                    'Time'
                );

                $connection->createTable($table);
            }
            catch(Zend_Db_Exception $e) {
                die($e->getMessage());
            }
        }
    }
}
