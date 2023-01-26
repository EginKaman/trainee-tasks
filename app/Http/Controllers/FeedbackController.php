<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Mail\Feedback;
use App\Models\Message;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('feedback');
    }

    /**
     * @param FeedbackRequest $request
     * @return Redirector|Application|RedirectResponse
     */
    public function store(FeedbackRequest $request): Redirector|Application|RedirectResponse
    {
        $message = new Message($request->validated());
        $message->save();

        Mail::mailer($message->method)
            ->send(new Feedback($message));

        return redirect(route('feedback'))
            ->with('success', true);
    }
}
