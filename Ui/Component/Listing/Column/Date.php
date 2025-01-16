<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Ui\Component\Listing\Column;

use Exception;

class Date extends \Magento\Ui\Component\Listing\Columns\Date
{
    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource)
    {
        try {
            return parent::prepareDataSource($dataSource);
        } catch (Exception $e) {
            return $dataSource;
        }
    }
}
