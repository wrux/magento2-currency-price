<?php

namespace Wrux\Currency\Observer;

use Magento\Directory\Block\Currency;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ProductAfterGetFinalPrice
 * @package Wrux\Currency\Observer
 */
class ProductAfterGetFinalPrice implements ObserverInterface
{
  /**
   * @var Currency
   */
  private $currency;

  /**
   * ProductAfterGetFinalPrice constructor.
   * @param Currency $currency
   */
  public function __construct(Currency $currency) {
    $this->currency = $currency;
  }

  /**
   * @param Observer $observer
   * @return $this|void
   */
  public function execute(Observer $observer) {
    $product = $observer->getProduct();
    $currencyCode = $this->currency->getCurrentCurrencyCode();
    $cur = strtolower($currencyCode);
    if (!empty($product->getData('compound_price_' . $cur)) && $product->getData('compound_price_' . $cur) != $product->getData('final_price')) {
      $product->setFinalPrice($product->getData('compound_price_' . $cur));
    }
    return $this;
  }
}