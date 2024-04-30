<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface CsoptfdInterface
{
    const COLUMN_OPTFIELD  = 'OPTFIELD';

    const COLUMN_VALUE     = 'VALUE';

    const COLUMN_SORTEDVAL = 'SORTEDVAL';

    const COLUMN_VDESC     = 'VDESC';

    const COLUMN_TYPE      = 'TYPE';

    const COLUMN_LENGTH    = 'LENGTH';

    const COLUMN_DECIMALS  = 'DECIMALS';

    const COLUMN_ALLOWNULL = 'ALLOWNULL';

    const COLUMN_VALIDATE  = 'VALIDATE';

    const TYPE_TEXT        = 1;

    const TYPE_AMOUNT      = 100;

    const TYPE_NUMBER      = 6;

    const TYPE_INTEGER     = 8;

    const TYPE_YESNO       = 9;

    const TYPE_DATE        = 3;

    const TYPE_TIME        = 4;
}
