<?php

namespace App;

enum ContactUsStatus: string
{
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
}
