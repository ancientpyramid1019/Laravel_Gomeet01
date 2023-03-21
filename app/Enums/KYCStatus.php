<?php

namespace App\Enums;

enum KYCStatus: int
{
    case Verified = 1;
    case Pending = 2;
    case Failed = 3;
}
