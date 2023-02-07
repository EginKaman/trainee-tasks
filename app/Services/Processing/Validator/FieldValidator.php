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

    public function prepareValue(string $value, $field)
    {
        $value = trim($value);
        if ($field === self::NAME_FIELD) {
            return preg_replace('/\s+/', ' ', $value);
        }
        return preg_replace('/\s/', '', $value);
    }

    public function validate(string|object|array $object, string $field, ?int $line): static
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
                $answer = $fieldClass->{$method}((string)$this->getProperty($object, $field), $line);
                if (!is_bool($answer)) {
                    $this->errorBag->add($answer);
                }
                if ($fieldClass->break) {
                    break;
                }
            }
        } else {
            $this->errorBag->add($this->propertyNotExistError($field, $line));
        }

        return $this;
    }

    public function hasErrors(): bool
    {
        return $this->errorBag->isNotEmpty();
    }

    /**
     * @return Error[]
     */
    public function errors(): array
    {
        return $this->errorBag->errors();
    }

    protected function isExistProperty(string|object|array $object, string $property): bool
    {
        if (is_array($object)) {
            return isset($object[$property]);
        }
        if (is_object($object) || is_string($object)) {
            return property_exists($object, $property);
        }

        return false;
    }

    protected function getProperty(object|array $object, string $property)
    {
        if (is_array($object)) {
            return $this->prepareValue($object[$property], $property);
        }

        return $this->prepareValue((string)$object->{$property}, $property);
    }

    protected function getMethods(object|string $class): array
    {
        return get_class_methods($class);
    }

    protected function propertyNotExistError(string $property, $line): Error
    {
        return new Error("Field {$property} is not exist in file", $line);
    }

    protected function hasSecond($fieldClass): bool
    {
        $secondField = $this->isExistProperty($fieldClass, 'secondField');
        $secondValue = $this->isExistProperty($fieldClass, 'secondValue');

        return $secondValue === true && $secondField === true;
    }

    protected function second($fieldClass, $object)
    {
        $fieldClass->secondValue = $this->getProperty($object, (string)$fieldClass->secondField);

        return $fieldClass;
    }
}
