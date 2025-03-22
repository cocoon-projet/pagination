<?php

declare(strict_types=1);

/*
 * LICENSE
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous license MIT.
 * Consulter le fichier LICENSE du project. LICENSE.txt.
 *
 */
namespace Cocoon\Pager\Styling\Framework;

use Cocoon\Pager\Styling\CssFrameworkInterface;

/**
 * Factory pour créer les instances de frameworks CSS
 */
class CssFrameworkFactory
{
    /**
     * Crée une instance du framework CSS demandé
     *
     * @param string $framework bootstrap4|bootstrap5|tailwind
     * @return CssFrameworkInterface
     * @throws \InvalidArgumentException Si le framework CSS n'est pas supporté
     */
    public static function create(string $framework): CssFrameworkInterface
    {
        return match ($framework) {
            'bootstrap4' => new Bootstrap4(),
            'bootstrap5' => new Bootstrap5(),
            'tailwind' => new Tailwind(),
            default => throw new \InvalidArgumentException(
                "Framework CSS non supporté: {$framework}. Utilisez 'bootstrap4', 'bootstrap5' ou 'tailwind'"
            ),
        };
    }
}
