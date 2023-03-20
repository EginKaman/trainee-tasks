<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionCollection;
use App\Repositories\SubscriptionRepository;

class SubscriptionController extends Controller
{
    public function index(): SubscriptionCollection
    {
        return new SubscriptionCollection(SubscriptionRepository::getSubscriptions());
    }
}
