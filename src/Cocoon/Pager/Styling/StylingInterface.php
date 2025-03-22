<?php

declare(strict_types=1);

namespace Cocoon\Pager\Styling;

use Cocoon\Pager\Pager;

/**
 * Interface pour les styles de pagination
 *
 * Cette interface définit les méthodes requises pour un style de pagination.
 */
interface StylingInterface
{
    /**
     * Rend la pagination avec le style spécifique
     */
    public function render(Pager $pager): string;
}
