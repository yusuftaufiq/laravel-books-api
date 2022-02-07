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

    protected array $arrayable;

    protected array $countable;

    /**
     * Get the collection by its value.
     *
     * @param mixed $value
     *
     * @return static
     */
    abstract public function find(mixed $value): static;

    public function count(): int
    {
        return (int) collect($this->countable)->every(fn ($key) => $this->{$key} !== null);
    }

    public function toArray(): array
    {
        return collect($this->arrayable)->mapWithKeys(fn ($key) => [$key => $this->{$key}])->toArray();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
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
