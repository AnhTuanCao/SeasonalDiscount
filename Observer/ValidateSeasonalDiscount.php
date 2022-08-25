<?php

namespace Magenest\SeasonalDiscount\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;

class ValidateSeasonalDiscount implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ManagerInterface $messageManager,
        SerializerInterface $serializer

    )
    {
        $this->messageManager = $messageManager;
        $this->serializer = $serializer;
    }

    protected $proceed = false;
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->proceed) {
            return;
        }
        $this->proceed = true;
        $object = $observer->getEvent()->getData('data_object');
        $datas = $object->getSaleDay();
        $object->setData('sale_day', $this->serializer->serialize($datas));
        $this->messageManager->addSuccessMessage('Success');
    }
}
