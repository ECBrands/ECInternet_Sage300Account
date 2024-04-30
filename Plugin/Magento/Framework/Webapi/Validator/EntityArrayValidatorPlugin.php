<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\Magento\Framework\Webapi\Validator;

use Magento\Framework\Webapi\Validator\EntityArrayValidator;

class EntityArrayValidatorPlugin
{
    /**
     * @var string[]
     */
    private $allowedClasses = [
        '\ECInternet\Sage300Account\Api\Data\AroblInterface',
        '\ECInternet\Sage300Account\Api\Data\ArtcpInterface',
        '\ECInternet\Sage300Account\Api\Data\OeinvdInterface',
        '\ECInternet\Sage300Account\Api\Data\OeinvhInterface',
        '\ECInternet\Sage300Account\Api\Data\OeorddInterface',
        '\ECInternet\Sage300Account\Api\Data\OeordhInterface',
        '\ECInternet\Sage300Account\Api\Data\OeppreInterface',
        '\ECInternet\Sage300Account\Api\Data\OeshdtInterface',
        '\ECInternet\Sage300Account\Api\Data\OetermiInterface',
        '\ECInternet\Sage300Account\Api\Data\PoporhInterface',
        '\ECInternet\Sage300Account\Api\Data\PoporlInterface',
        '\ECInternet\Sage300Account\Api\Data\PoporloInterface',
    ];

    /**
     * Skip complex array-type validation for our custom interfaces
     *
     * @param EntityArrayValidator $subject
     * @param callable             $proceed
     * @param string               $className
     * @param array                $items
     *
     * @return void
     */
    public function aroundValidateComplexArrayType(
        /* @noinspection PhpUnusedParameterInspection */ EntityArrayValidator $subject,
        callable $proceed,
        string $className,
        array $items
    ) {
        if (!in_array($className, $this->allowedClasses)) {
            $proceed($className, $items);
        }
    }
}
