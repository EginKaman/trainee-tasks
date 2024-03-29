<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator;

use Illuminate\Support\Str;

class FieldValidator
{
    public const LAST_UPDATE_FIELD = 'lastUpdate';
    public const NAME_FIELD = 'name';
    public const UNIT_FIELD = 'unit';
    public const COUNTRY_FIELD = 'country';
    public const CURRENCY_CODE_FIELD = 'currencyCode';
    public const RATE_FIELD = 'rate';
    public const CHANGE_FIELD = 'change';
    public array $fields = ['name', 'lastUpdate', 'unit', 'country', 'currencyCode', 'rate', 'change'];
    private ErrorBag $errorBag;

    public function __construct(ErrorBag $errorBag)
    {
        $this->errorBag = $errorBag;
    }

    public function prepareValue(string $value, string $field): array|string|null
    {
        $value = trim($value);
        if ($field === self::NAME_FIELD) {
            return preg_replace('/\s+/', ' ', $value);
        }

        if ($field === self::RATE_FIELD || $field === self::CHANGE_FIELD) {
            $value = Str::replace(',', '.', $value);
        }

        return preg_replace('/\s/', '', $value);
    }

    public function validate(object|array $object, string $field, ?int $line): static
    {
        if ($this->isExistProperty($object, $field)) {
            $fieldClass = 'App\Services\Processing\Validator\Fields\\' . Str::studly($field);
            $fieldClass = new $fieldClass();
            foreach ($this->getMethods($fieldClass) as $method) {
                if ($this->hasSecond($fieldClass)) {
                    if (!$this->isExistProperty($object, $fieldClass->secondField)) {
                        break;
                    }
                    $fieldClass = $this->second($fieldClass, $object);
                }

                $answer = $fieldClass->{$method}((string) $this->getProperty($object, $field), $line);

                if (!is_bool($answer)) {
                    $this->addError($answer);
                }

                if ($fieldClass->break) {
                    break;
                }
            }
        } else {
            $this->addError($this->propertyNotExistError($field, $line));
        }

        return $this;
    }

    public function unique(object|array $currencies, string $field, int $line): bool
    {
        $uniques = [];
        foreach ($currencies as $currency) {
            if (!$this->isExistProperty($currency, $field)) {
                continue;
            }
            $value = $this->getProperty($currency, $field);
            if (!isset($uniques[$value])) {
                $uniques[$value] = $value;
            } else {
                $this->addError(new Error("Value of {$field} is existed in this date", $line));

                return false;
            }
        }

        return true;
    }

    public function addError(Error $error): void
    {
        $this->errorBag->add($error);
    }

    public function hasErrors(): bool
    {
        return !$this->errorBag->isNotEmpty();
    }

    /**
     * @return Error[]
     */
    public function errors(): array
    {
        return $this->errorBag->errors();
    }

    protected function isExistProperty(object|array $object, string $property): bool
    {
        if (is_array($object)) {
            return isset($object[$property]);
        }

        return property_exists($object, $property);
    }

    protected function getProperty(object|array $object, string $property): array|string
    {
        if (is_array($object)) {
            return $this->prepareValue((string) $object[$property], $property);
        }

        return $this->prepareValue((string) $object->{$property}, $property);
    }

    protected function getMethods(object|string $class): array
    {
        return get_class_methods($class);
    }

    protected function propertyNotExistError(string $property, int $line): Error
    {
        return new Error("Field {$property} is not exist in file", $line);
    }

    protected function hasSecond(object $fieldClass): bool
    {
        $secondField = $this->isExistProperty($fieldClass, 'secondField');
        $secondValue = $this->isExistProperty($fieldClass, 'secondValue');

        return $secondValue === true && $secondField === true;
    }

    protected function second(object $fieldClass, object|array $object): object
    {
        $fieldClass->secondValue = $this->getProperty($object, (string) $fieldClass->secondField);

        return $fieldClass;
    }
}
