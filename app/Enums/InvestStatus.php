<?php

namespace App\Enums;

enum InvestStatus: string
{
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Pending = 'pending';
}
