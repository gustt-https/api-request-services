<?php

namespace App\Enums;

enum RequestStatus: string 
{   
    case AWAIT_PAYMENT = 'await_payment';
    case SEARCHING = 'searching';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
}
