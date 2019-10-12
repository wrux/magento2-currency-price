<?php


namespace Wrux\Currency\Setup;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Backend\Price;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\State;

/**
 * Class UpgradeData
 * @package Wrux\Currency\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
  /**
   * @var EavSetupFactory
   */
  private $eavSetupFactory;

  /**
   * @var Currency
   */
  private $currency;

  /**
   * @var State
   */
  private $state;

  /**
   * UpgradeData constructor.
   * @param EavSetupFactory $eavSetupFactory
   * @param Currency $currency
   * @param State $state
   */
  public function __construct(
    EavSetupFactory $eavSetupFactory,
    Currency $currency,
    State $state
  ) {
    $this->state = $state;
    $this->currency = $currency;
    $this->eavSetupFactory = $eavSetupFactory;
  }

  /**
  * {@inheritdoc}
  */
  public function upgrade(
    ModuleDataSetupInterface $setup,
    ModuleContextInterface $context
  ) {
    if (version_compare($context->getVersion(), "0.0.2", "<")) {
      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $this->state->emulateAreaCode(
        \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
        [$this, 'createAttr'],
        [$eavSetup]
      );
    }
  }

  /**
   * @param $setup
   */
  public function createAttr($setup) {
    $currencies = $this->currency->getConfigAllowCurrencies();
    foreach ($currencies as $currency) {
      $cur = strtolower($currency);
      $setup->addAttribute(
        Product::ENTITY,
        'compound_price_' . $cur,
        [
          'type' => 'decimal',
          'group' => 'Advanced Pricing',
          'backend' => Price::class,
          'frontend' => '',
          'label' => 'compound_price_' . $cur,
          'input' => 'price',
          'class' => '',
          'source' => '',
          'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
          'visible' => true,
          'required' => false,
          'user_defined' => false,
          'default' => '',
          'searchable' => false,
          'filterable' => false,
          'comparable' => false,
          'visible_on_front' => false,
          'used_in_product_listing' => true,
          'unique' => false,
          'apply_to' => ''
        ]
      );
    }
  }
}
