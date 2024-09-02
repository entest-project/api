<?php

declare(strict_types=1);

namespace App\Entity\DataType;

enum FakeDataType: string
{
    case FirstName = 'first_name';

    case LastName = 'last_name';

    case FullName = 'full_name';

    case JobTitle = 'job_title';

    case Email = 'email';

    case Phone = 'phone';

    case Number = 'number';

    case UUID = 'uuid';

    case StreetAddress = 'street_address';

    case City = 'city';

    case ZipCode = 'zip_code';

    case Country = 'country';

    case Latitude = 'latitude';

    case Longitude = 'longitude';

    case Url = 'url';
}
