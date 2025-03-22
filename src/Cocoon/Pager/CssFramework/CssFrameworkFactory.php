<?php

declare(strict_types=1);

namespace Cocoon\Pager\CssFramework;

use InvalidArgumentException;

/**
 * Factory pour créer les instances de frameworks CSS
 */
final class CssFrameworkFactory
{
    /**
     * Crée une instance du framework CSS demandé
     *
     * @throws InvalidArgumentException Si le framework n'est pas supporté
     */
    public function create(string $framework): CssFrameworkInterface
    {
        return match ($framework) {
            'bootstrap4' => new Bootstrap4(),
            'bootstrap5' => new Bootstrap5(),
            'tailwind' => new Tailwind(),
            default => throw new InvalidArgumentException(
                sprintf('Framework CSS non supporté : %s', $framework)
            )
        };
    }
}
