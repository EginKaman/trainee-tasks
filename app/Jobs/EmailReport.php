<?php

namespace App\Jobs;

use App\Mail\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $availableDates = $this->getLogFileDates();
        $date = Date::yesterday()->format('Y-m-d');
        $message = 'Errors was found with selected date ' . $date;
        $logs = [];
        $filePath = null;

        if (count($availableDates) > 0 || !isset($availableDates[$date])) {
            $message = 'No log available';
        } else {
            $pattern = "/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)/m";

            $fileName = 'laravel-' . $date . '.log';
            $filePath = storage_path('logs/' . $fileName);
            $content = file_get_contents($filePath);
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);

            foreach ($matches as $match) {
                $match['message'] = trim($match['message']);
                $slug = Str::slug($match['message']);
                $count = (isset($logs[$slug]['count'])) ? $logs[$slug]['count']++ : 1;
                $logs[$slug] = [
                    'count' => $count,
                    'env' => $match['env'],
                    'type' => $match['type'],
                    'message' => trim($match['message'])
                ];
            }
        }

        Mail::send(new Report($message, $logs, $filePath));
    }

    /**
     * Returned array with log file dates
     *
     * @return array
     */
    public function getLogFileDates(): array
    {
        $dates = [];
        $files = glob(storage_path('logs/laravel-*.log'));
        $files = array_reverse($files);
        foreach ($files as $path) {
            $fileName = basename($path);
            preg_match('/(?<=laravel-)(.*)(?=.log)/', $fileName, $dtMatch);
            $date = $dtMatch[0];
            $dates[$date] = $date;
        }

        return $dates;
    }
}
