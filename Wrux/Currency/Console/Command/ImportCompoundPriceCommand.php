<?php

namespace Wrux\Currency\Console\Command;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Magento\Framework\App\Area;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Action;

/**
 * Class ImportCompoundPriceCommand
 * @package Wrux\Currency\Console\Command
 */
class ImportCompoundPriceCommand extends Command
{
  /**
   * @var State
   */
  protected $appState;

  /**
   * @var CollectionFactory
   */
  protected $collectionFactory;

  /**
   * @var EACCofig
   */
  protected $eavConfig;

  /**
   * @var ProductRepositoryInterface
   */
  protected $productRepository;

  /**
   * @var ProductFactory
   */
  protected $productFactory;

  /**
   * @var Action
   */
  protected $productAction;

  /**
   * @var Csv
   */
  protected $csv;

  /**
   * ImportCompoundPriceCommand constructor.
   *
   * @param State $appState
   * @param Csv $csvProcessor
   * @param DirectoryList $directoryList
   * @param Filesystem $filesystem
   * @param ProductFactory $productFactory
   * @param ProductRepositoryInterface $productRepository
   * @param CollectionFactory $collectionFactory
   * @param Action $productAction
   * @throws \Magento\Framework\Exception\FileSystemException
   */
  public function __construct(
    State $appState,
    Csv $csvProcessor,
    DirectoryList $directoryList,
    Filesystem $filesystem,
    ProductFactory $productFactory,
    ProductRepositoryInterface $productRepository,
    CollectionFactory $collectionFactory,
    Action $productAction
  ) {
    $this->appState = $appState;
    $this->csvProcessor = $csvProcessor;
    $this->jsonDir = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    $this->directoryList = $directoryList;
    $this->filesystem = $filesystem;
    $this->productFactory = $productFactory;
    $this->productRepository = $productRepository;
    $this->collectionFactory = $collectionFactory;
    $this->productAction = $productAction;
    $this->csv = $csvProcessor;
    parent::__construct();
  }

  /**
   * Configure the command.
   */
  protected function configure() {
    $this->setName('currency:import:prices')
      ->setDescription('Import Currency Prices');
  }

  /**
   * Execute the command.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null
   * @throws \Magento\Framework\Exception\CouldNotSaveException
   * @throws \Magento\Framework\Exception\FileSystemException
   * @throws \Magento\Framework\Exception\InputException
   * @throws \Magento\Framework\Exception\LocalizedException
   * @throws \Magento\Framework\Exception\StateException
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->appState->setAreaCode(Area::AREA_ADMINHTML);
    $fileDirectoryPath = $this->directoryList->getPath(DirectoryList::VAR_DIR);
    $fileName = 'currency.csv';
    $names = [];
    $currency = ['eur', 'gbp', 'usd'];
    if (!file_exists($fileDirectoryPath . '/' . $fileName)) {
      $output->writeln('Import file does not exist.');
      return 0;
    }
    $csvData = $this->csv->getData($fileDirectoryPath . '/' . $fileName);
    if (empty($csvData)) {
      $output->writeln('Empty or incorrect data.');
      return 0;
    }

    // Init the progress bar
    $progressBar = new ProgressBar($output, count($csvData));
    // Start and display the progress bar
    $progressBar->start();

    foreach ($csvData as $row => $data) {
      if ($row == 0) {
        $names = array_flip($data);
      } else {
        if (!isset($data[$names['sku']])) {
          $output->writeln('SKU not found.');
          continue;
        }
        try {
          $product = $this->productRepository->get($data[$names['sku']]);
        } catch (NoSuchEntityException $e){
          $product = false;
          $output->writeln(sprintf('Product %s not found.', $data[$names['sku']]));
        }
        if ($product && $product->getId()) {
          foreach ($currency as $cur) {
            $key = 'compound_price_' . $cur;
            if (!empty($data[$names[$key]])) {
              $product->setData($key, $data[$names[$key]]);
            }
          }
          $this->productRepository->save($product);
        }
      }
      $progressBar->advance();
    }

    $progressBar->finish();
    return 0;
  }
}
