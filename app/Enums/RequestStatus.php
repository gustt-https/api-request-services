<?php

namespace App\Enums;

enum RequestStatus: string 
{
    case SEARCHING = 'searching';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
}
