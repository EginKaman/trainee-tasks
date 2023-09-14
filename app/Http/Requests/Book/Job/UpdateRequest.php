<?php

declare(strict_types=1);

namespace App\Http\Requests\Book\Job;

use App\DataTransferObjects\Book\JobData;
use App\Enum\JobType;
use App\Models\Job;
use Closure;
use Cron\CronExpression;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Job $job
 */
class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'workers_count' => ['bail', 'required', 'integer', 'min:1', 'max:1000'],
            'cron' => ['bail', 'sometimes', Rule::requiredIf($this->job->type === JobType::Cron), 'array', 'size:5'],
            'cron.min' => [
                'bail', Rule::requiredIf($this->job->type === JobType::Cron), Rule::excludeIf(
                    $this->job->type !== JobType::Cron
                ), 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("{$value} * * * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.hour' => [
                'bail', Rule::requiredIf($this->job->type === JobType::Cron), Rule::excludeIf(
                    $this->job->type !== JobType::Cron
                ), 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* {$value} * * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.day' => [
                'bail', Rule::requiredIf($this->job->type === JobType::Cron), Rule::excludeIf(
                    $this->job->type !== JobType::Cron
                ), 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * {$value} * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.month' => [
                'bail', Rule::requiredIf($this->job->type === JobType::Cron), Rule::excludeIf(
                    $this->job->type !== JobType::Cron
                ), 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * * {$value} *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.week' => [
                'bail', Rule::requiredIf($this->job->type === JobType::Cron), Rule::excludeIf(
                    $this->job->type !== JobType::Cron
                ), 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * * * {$value}")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
        ];
    }

    public function getValidatedData(): JobData
    {
        return JobData::from([
            ...$this->validated(), ...$this->job->toArray(),  'cron' => implode(' ', $this->validated('cron')),
        ]);
    }
}
