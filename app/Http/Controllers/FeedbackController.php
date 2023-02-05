<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Mail\Feedback;
use App\Models\Message;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('feedback');
    }

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
