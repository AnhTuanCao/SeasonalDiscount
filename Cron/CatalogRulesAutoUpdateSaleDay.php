<?php

namespace Magenest\SeasonalDiscount\Cron;

use Magento\Backend\App\Config;
use Magento\Framework\Message\ManagerInterface;
use Magento\CatalogRule\Model\Rule;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Serialize\SerializerInterface;

class CatalogRulesAutoUpdateSaleDay
{
    /**
     * @param Rule $rule
     * @param CollectionFactory $collectionFactory
     * @param ManagerInterface $messageManager
     * @param Config $config
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Rule         $rule,
        CollectionFactory   $collectionFactory,
        ManagerInterface    $messageManager,
        Config              $config,
        SerializerInterface $serializer
    )
    {
        $this->rule = $rule;
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    public function execute()
    {
        $collection = $this->rule->create()->getCollection()->addFieldToFilter('sale_day', ['notnull' => true]);
        $specialRules = $collection->getItems();
        foreach ($specialRules as $specialRule) {
            $dates = $this->serializer->unserialize($specialRule->getSaleDay());
            usort($dates, function ($a, $b) {
                return $a->month_picker < $b->month_picker ? -1 : 1;
            });
            $toDate = $specialRule->getToDate();
            $year = date('Y', strtotime($specialRule->getFromDate()));
            foreach ($dates as $date) {
                $saleDay = $year . '-' . $date->{'month_picker'} . '-' . $date->{'day_picker'};
                if ($saleDay > $toDate) {
                    $specialRule->setFromDate($saleDay);
                    $specialRule->setToDate($saleDay);
                    $specialRule->save();
                    break;
                }
            }
        }
    }
}
