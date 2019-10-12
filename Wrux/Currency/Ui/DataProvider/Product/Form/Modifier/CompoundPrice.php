<?php

namespace Wrux\Currency\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Customer\Source\GroupSourceInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\Currency;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Container;

/**
 * Class CompoundPrice
 * @package Wrux\Currency\Ui\DataProvider\Product\Form\Modifier
 */
class CompoundPrice extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AdvancedPricing
{
  /**
   * @var Currency
   */
  protected $currency;

  /**
   * @var CurrencyInterface
   */
  protected $localeCurrency;

  public function __construct(
    LocatorInterface $locator,
    StoreManagerInterface $storeManager,
    GroupRepositoryInterface $groupRepository,
    GroupManagementInterface $groupManagement,
    SearchCriteriaBuilder $searchCriteriaBuilder,
    ModuleManager $moduleManager,
    Data $directoryHelper,
    ArrayManager $arrayManager,
    Currency $currency,
    CurrencyInterface $localeCurrency,
    $scopeName = '',
    GroupSourceInterface $customerGroupSource = null
  ) {
    $this->currency = $currency;
    $this->localeCurrency = $localeCurrency;
    parent::__construct($locator, $storeManager, $groupRepository, $groupManagement, $searchCriteriaBuilder, $moduleManager, $directoryHelper, $arrayManager, $scopeName, $customerGroupSource);
  }

  /**
   * {@inheritdoc}
   * @since 101.0.0
   */
  public function modifyMeta(array $meta) {
    parent::modifyMeta($meta);
    if (isset($this->meta['advanced_pricing_modal'])) {
      $this->addCompoundPrice();
    }
    return $this->meta;
  }


  /**
   * Add compound price field.
   */
  protected function addCompoundPrice() {
    $this->meta = $this->arrayManager->merge(
      'advanced_pricing_modal/children/advanced-pricing/children',
      $this->meta,
      $this->getCompoundPriceStructure()
    );
  }

  /**
   * Get compound prices.
   *
   * @return array
   *   Return compound prices
   */
  protected function getCompoundPriceStructure() {
    $currencies = $this->currency->getConfigAllowCurrencies();
    $compoundPrices = [];
    if (!empty($currencies)) {
      foreach ($currencies as $currency) {
        $cur = strtolower($currency);
        $compoundPrices['container_compound_price_' . $cur] = [
          'arguments' => [
            'data' => [
              'config' => [
                'formElement' => 'container',
                'componentType' => Container::NAME,
                'breakLine' => false,
                'label' => 'Compound Price ' . $currency,
                'required' => '0',
                'sortOrder' => 30,
              ]
            ]
          ],
          'children' => [
            'compound_price_' . $cur => [
              'arguments' => [
                'data' => [
                  'config' => [
                    'dataType' => 'price',
                    'formElement' => 'input',
                    'visible' => '1',
                    'required' => '0',
                    'notice' => null,
                    'default' => null,
                    'label' => 'Compound Price ' . $currency,
                    'code' => 'compound_price_' . $cur,
                    'source' => 'advanced-pricing',
                    'scopeLabel' => null,
                    'globalScope' => true,
                    'sortOrder' => 30,
                    'componentType' => 'field',
                    'validation' => [
                        'validate-zero-or-greater' => true,
                    ],
                    'addbefore' => $this->localeCurrency->getCurrency($currency)->getSymbol()
                  ]
                ]
              ]
            ]
          ]
        ];
      }
    }
    return $compoundPrices;
  }
}
