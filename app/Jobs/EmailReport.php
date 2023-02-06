<?php declare(strict_types=1);

namespace App\Jobs;

use App\Mail\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Date, Log, Mail};
use Illuminate\Support\Str;

class EmailReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = Date::yesterday()->format('Y-m-d');
        $file = storage_path('logs/laravel-' . $date);
        $message = 'Errors was found with selected date ' . $date;
        $logs = [];
        $filePath = null;

        if (file_exists($file)) {
            $message = 'No log available';
        } else {
            $pattern = '/^\\[(?<date>.*)\\]\\s(?<env>\\w+)\\.(?<type>\\w+):(?<message>.*)/m';

            $content = file_get_contents($file);
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);

            foreach ($matches as $match) {
                $match['message'] = trim($match['message']);
                $slug = Str::slug($match['message']);
                $count = (isset($logs[$slug]['count'])) ? $logs[$slug]['count']++ : 1;
                $logs[$slug] = [
                    'count' => $count,
                    'env' => $match['env'],
                    'type' => $match['type'],
                    'message' => trim($match['message']),
                ];
            }
        }

        Mail::send(new Report($message, $logs, $filePath));
    }
}
