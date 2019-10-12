<?php


namespace Wrux\Currency\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package Wrux\Currency\Setup
 */
class InstallData implements InstallDataInterface
{
  /**
   * {@inheritdoc}
   */
  public function install(
    ModuleDataSetupInterface $setup,
    ModuleContextInterface $context
  ) {
    //Your install script
  }
}
