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
     * Get the collection by its value.
     *
     * @param string $value
     *
     * @return self
     */
    abstract public function find(string $value): self;

    public function count(): int
    {
        return (int) collect($this->countable)->every(fn ($key) => $this->{$key} !== null);
    }

    public function getRouteKey(): int|string
    {
        return $this->{$this->primaryKey};
    }

    public function getRouteKeyName(): string
    {
        return $this->getAttribute($this->getRouteKeyName());
    }

    public function resolveRouteBinding(mixed $value, $field = null): static
    {
        return $this->find($value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }
}
