<?php


namespace Wrux\Currency\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package Wrux\Currency\Setups
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
  /**
   * {@inheritdoc}
   */
  public function upgrade(
    SchemaSetupInterface $setup,
    ModuleContextInterface $context
  ) {
    if (version_compare($context->getVersion(), "1.0.0", "<")) {
      //Your upgrade script
    }
  }
}
