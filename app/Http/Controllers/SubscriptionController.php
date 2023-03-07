<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionCollection;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index(): SubscriptionCollection
    {
        return new SubscriptionCollection(Subscription::withTranslation()->get());
    }
}
