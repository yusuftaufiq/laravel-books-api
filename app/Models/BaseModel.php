<?php

namespace App\Models;

use App\Contracts\BaseModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

abstract class BaseModel implements BaseModelInterface
{
    use HasAttributes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $primaryKey;

    /**
     * Get the resource by its value.
     *
     * @param string $value
     *
     * @return self
     */
    abstract public function find(string $value): self;

    /**
     * Get the value of the model's route key.
     *
     * @return int|string
     */
    public function getRouteKey(): int|string
    {
        return $this->{$this->primaryKey};
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return $this->getAttribute($this->getRouteKeyName());
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     *
     * @return static
     */
    public function resolveRouteBinding(mixed $value, $field = null): static
    {
        return $this->find($value);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     *
     * @throws \Exception
     */
    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }
}
