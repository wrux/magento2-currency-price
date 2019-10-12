<?php

namespace Wrux\Currency\Model\Product;

use Magento\Directory\Block\Currency;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\PriceModifierInterface;

/**
 * Class PriceModifier
 * @package Wrux\Currency\Model\Product
 */
class PriceModifier implements PriceModifierInterface
{
  /**
   * @var Currency
   */
  private $currency;

  /**
   * PriceModifier constructor.
   * @param Currency $currency
   */
  public function __construct(Currency $currency) {
    $this->currency = $currency;
  }

  /**
   * Modify price.
   *
   * @param mixed $price
   * @param Product $product
   * @return mixed
   */
  public function modifyPrice($price, Product $product) {
    if ($price !== null) {
      $currencyCode = $this->currency->getCurrentCurrencyCode();
      $cur = strtolower($currencyCode);
      if (!empty($product->getData('compound_price_' . $cur)) && $product->getData('compound_price_' . $cur) != $product->getData('final_price')) {
        $price = min($product->getData('final_price'), $product->getData('compound_price_' . $cur));
      }
    }
    return $price;
  }
}