<?php
declare(strict_types=1);

namespace Sportires\Walmart\Cron;

class Sportireswalmartstock
{

    protected $logger;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Marketplaces-Walmart-Stock-'.date('Ymd').'.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $logger->info("Iniciando Proceso para Actualizar Inventario a Walmart");
        $logger->info(date('Y-m-d H:i:s'));

        $walmart = $objectManager->get('Sportires\Walmart\Helper\Configuration');
        $oAuth = $walmart->getToken();
        $walmartStock = $objectManager->get('Sportires\Walmart\Helper\Stock');
        $result = $walmartStock->bulkUpdate($oAuth);

        if(!empty($result->feedId)){
            $walmartStock->saveFeed($result->feedId);
        }

        $logger->info("RESPONSE : ".print_r($result,true));
        $logger->info('CONCLUYE : '.date('Y-m-d H:i:s'));
    }
}

