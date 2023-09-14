<?php

declare(strict_types=1);

namespace App\Http\Requests\Book\Job;

use App\DataTransferObjects\Book\JobData;
use App\Enum\JobType;
use Closure;
use Cron\CronExpression;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['bail', 'required', 'string', Rule::enum(JobType::class)],
            'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
            'workers_count' => ['bail', 'required', 'integer', 'min:1', 'max:1000'],
            'cron' => ['bail', 'sometimes', 'required_if:type,cron', 'array', 'size:5'],
            'cron.min' => [
                'bail', 'required_if:type,cron', 'exclude_if:type,single', 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("{$value} * * * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.hour' => [
                'bail', 'required_if:type,cron', 'exclude_if:type,single', 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* {$value} * * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.day' => [
                'bail', 'required_if:type,cron', 'exclude_if:type,single', 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * {$value} * *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.month' => [
                'bail', 'required_if:type,cron', 'exclude_if:type,single', 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * * {$value} *")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'cron.week' => [
                'bail', 'required_if:type,cron', 'exclude_if:type,single', 'string',
                function (string $attribute, string $value, Closure $fail): void {
                    if (!CronExpression::isValidExpression("* * * * {$value}")) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'is_loop' => ['bail', 'sometimes', 'boolean'],
            'pause' => [
                'bail', 'exclude_unless:loop,true',  'required_if:is_loop,true', 'integer', 'min:0', 'max:1000',
            ],
            'repetitions' => [
                'bail', 'exclude_unless:loop,true', 'required_if:is_loop,true', 'integer', 'min:0', 'max:1000',
            ],
        ];
    }

    public function getValidatedData(): JobData
    {
        return JobData::from([
            ...$this->validated(), 'cron' => implode(' ', $this->validated('cron')),
        ]);
    }
}
