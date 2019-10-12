<?php

namespace Wrux\Currency\Plugin\Product;

use Magento\Directory\Block\Currency;

/**
 * Class CompoundPricePlugin
 * @package Wrux\Currency\Plugin\Product
 */
class CompoundPricePlugin
{
  /**
   * @var Currency
   */
  private $currency;

  /**
   * CompoundPricePlugin constructor.
   * @param Currency $currency
   */
  public function __construct(
    Currency $currency
  ) {
    $this->currency = $currency;
  }

  /**
   * @param $product
   * @param $proceed
   * @return mixed
   */
  public function afterGetPrice($product, $proceed) {
    $currencyCode = $this->currency->getCurrentCurrencyCode();
    $cur = strtolower($currencyCode);
    if (!empty($product->getData('compound_price_' . $cur)) && $product->getData('compound_price_' . $cur) != $product->getData('price')) {
      $product->setPrice($product->getData('compound_price_' . $cur));
    }
    return $product->getData('price');
  }
}