<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface CsoptfhInterface
{
    const COLUMN_OPTFIELD  = 'OPTFIELD';

    const COLUMN_FDESC     = 'FDESC';

    const COLUMN_TYPE      = 'TYPE';

    const COLUMN_LENGTH    = 'LENGTH';

    const COLUMN_ALLOWNULL = 'ALLOWNULL';

    const COLUMN_VALIDATE  = 'VALIDATE';

    const COLUMN_VALUES    = 'VALUES';

    const TYPE_TEXT        = 1;

    const TYPE_AMOUNT      = 100;

    const TYPE_NUMBER      = 6;

    const TYPE_INTEGER     = 8;

    const TYPE_YESNO       = 9;

    const TYPE_DATE        = 3;

    const TYPE_TIME        = 4;
}
