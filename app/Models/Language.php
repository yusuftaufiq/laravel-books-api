<?php

namespace App\Models;

use App\Contracts\LanguageInterface;
use App\Enums\LanguageEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Language extends BaseModel implements LanguageInterface
{
    protected string $primaryKey = 'slug';

    protected array $arrayable = [
        'slug',
        'name',
    ];

    protected array $countable = [
        'slug',
        'name',
    ];

    final public function __construct(
        public ?string $slug = null,
        public ?string $name = null,
        public ?int $value = null,
    ) {
    }

    final public function all(): array
    {
        $categories = collect(LanguageEnum::cases())->map(fn (LanguageEnum $languageEnum) => [
            'slug' => $languageEnum->value,
            'name' => $languageEnum->name,
            'value' => $languageEnum->value(),
        ]);

        return $categories->toArray();
    }

    final public function find(string $slug): static
    {
        $languageEnum = LanguageEnum::tryFrom($slug);

        if ($languageEnum === null) {
            throw new NotFoundHttpException('Language not found');
        }

        $this->slug = $languageEnum->value;
        $this->name = $languageEnum->name;
        $this->value = $languageEnum->value();

        return $this;
    }
}
