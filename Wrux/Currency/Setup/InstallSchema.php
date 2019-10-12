<?php


namespace Wrux\Currency\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Wrux\Currency\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

  /**
   * {@inheritdoc}
   */
  public function install(
    SchemaSetupInterface $setup,
    ModuleContextInterface $context
  ) {
    $installer = $setup;
    $installer->startSetup();

    /**
     * Create table 'catalog_product_currency_price'
     */
    $table = $installer->getConnection()
      ->newTable(
        $installer->getTable('catalog_product_currency_price')
      )
      ->addColumn(
        'product_id',
        Table::TYPE_INTEGER,
        10,
        ['unsigned' => true, 'nullable' => false, 'primary' => true],
        'Product ID'
      )
      ->addColumn(
        'currency',
        Table::TYPE_TEXT,
        3,
        ['nullable' => false,'primary' => true],
        'Currency'
      )
      ->addColumn(
        'price',
        Table::TYPE_DECIMAL,
        [12,4],
        [ 'nullable' => true],
        'Price'
      )
      ->addForeignKey(
        $installer->getFkName('catalog_product_currency_price', 'product_id', 'catalog_product_entity', 'entity_id'),
        'product_id',
        'catalog_product_entity',
        'entity_id',
        Table::ACTION_CASCADE
      );
    $installer->getConnection()->createTable($table);

  /**
   * Create table 'catalog_product_compound_price'
   */
  $table = $installer->getConnection()
    ->newTable(
      $installer->getTable('catalog_product_compound_price')
    )
    ->addColumn(
      'product_id',
      Table::TYPE_INTEGER,
      10,
      ['unsigned' => true, 'nullable' => false, 'primary' => true],
      'Product ID'
    )
    ->addColumn(
      'currency',
      Table::TYPE_TEXT,
      3,
      ['nullable' => false,'primary' => true],
      'Currency'
    )
    ->addColumn(
      'website_id',
      Table::TYPE_SMALLINT,
      5,
      ['unsigned' => true, 'nullable' => false, 'default' => 0],
      'Website ID'
    )
    ->addColumn(
      'price',
      Table::TYPE_DECIMAL,
      [12,4],
      [ 'nullable' => true],
      'Price'
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_compound_price', 'product_id', 'catalog_product_entity', 'entity_id'),
      'product_id',
      'catalog_product_entity',
      'entity_id',
      Table::ACTION_CASCADE
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_compound_price', 'website_id', 'store_website', 'website_id'),
      'website_id',
      'store_website',
      'website_id',
      Table::ACTION_CASCADE
    );
    $installer->getConnection()->createTable($table);

  /**
   * Create table 'catalog_product_compound_special_price'
   */
  $table = $installer->getConnection()
    ->newTable(
      $installer->getTable('catalog_product_compound_special_price')
    )
    ->addColumn(
      'product_id',
      Table::TYPE_INTEGER,
      10,
      ['unsigned' => true, 'nullable' => false, 'primary' => true],
      'Product ID'
    )
    ->addColumn(
      'currency',
      Table::TYPE_TEXT,
      3,
      ['nullable' => false,'primary' => true],
      'Currency'
    )
    ->addColumn(
      'website_id',
      Table::TYPE_SMALLINT,
      5,
      ['unsigned' => true, 'nullable' => false, 'default' => 0],
      'Wenbite ID'
    )
    ->addColumn(
      'price',
      Table::TYPE_DECIMAL,
      [12,4],
      [ 'nullable' => true],
      'Price'
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_compound_special_price', 'product_id', 'catalog_product_entity', 'entity_id'),
      'product_id',
      'catalog_product_entity',
      'entity_id',
      Table::ACTION_CASCADE
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_compound_special_price', 'website_id', 'store_website', 'website_id'),
      'website_id',
      'store_website',
      'website_id',
      Table::ACTION_CASCADE
    );
  $installer->getConnection()->createTable($table);

  /**
   * Create table 'catalog_product_index_compound_price'
   */
  $table = $installer->getConnection()
    ->newTable(
      $installer->getTable('catalog_product_index_compound_price')
    )
    ->addColumn(
      'entity_id',
      Table::TYPE_INTEGER,
      10,
      ['unsigned' => true, 'nullable' => false, 'primary' => true],
      'Product ID'
    )
    ->addColumn(
      'currency',
      Table::TYPE_TEXT,
      3,
      ['nullable' => false,'primary' => true],
      'Currency'
    )
    ->addColumn(
      'website_id',
      Table::TYPE_SMALLINT,
      5,
      ['unsigned' => true, 'nullable' => false, 'default' => 0],
      'Website ID'
    )
    ->addColumn(
      'price',
      Table::TYPE_DECIMAL,
      [12,4],
      [ 'nullable' => true],
      'Price'
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_index_compound_price', 'entity_id', 'catalog_product_entity', 'entity_id'),
      'entity_id',
      'catalog_product_entity',
      'entity_id',
      Table::ACTION_CASCADE
    )

    ->addForeignKey(
      $installer->getFkName('catalog_product_index_compound_price', 'website_id', 'store_website', 'website_id'),
      'website_id',
      'store_website',
      'website_id',
      Table::ACTION_CASCADE
    );
    $installer->getConnection()->createTable($table);

  /**
   * Create table 'catalog_product_index_compound_special_price'
   */
  $table = $installer->getConnection()
    ->newTable(
      $installer->getTable('catalog_product_index_compound_special_price')
    )
    ->addColumn(
      'entity_id',
      Table::TYPE_INTEGER,
      10,
      ['unsigned' => true, 'nullable' => false, 'primary' => true],
      'Product ID'
    )
    ->addColumn(
      'currency',
      Table::TYPE_TEXT,
      3,
      ['nullable' => false,'primary' => true],
      'Currency'
    )
    ->addColumn(
      'website_id',
      Table::TYPE_SMALLINT,
      5,
      ['unsigned' => true, 'nullable' => false, 'default' => 0],
      'Website ID'
    )
    ->addColumn(
      'price',
      Table::TYPE_DECIMAL,
      [12,4],
      [ 'nullable' => true],
      'Price'
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_index_compound_special_price', 'entity_id', 'catalog_product_entity', 'entity_id'),
      'entity_id',
      'catalog_product_entity',
      'entity_id',
      Table::ACTION_CASCADE
    )
    ->addForeignKey(
      $installer->getFkName('catalog_product_index_compound_special_price', 'website_id', 'store_website', 'website_id'),
      'website_id',
      'store_website',
      'website_id',
      Table::ACTION_CASCADE
    );
    $installer->getConnection()->createTable($table);
  }
}
