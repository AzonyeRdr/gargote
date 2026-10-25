<?php

namespace App\Services;

use App\Exceptions\RegleMetierException;
use App\Models\PlatDuJourModel;
use App\Models\PlatModel;

class PlatDuJourService
{
    /** Définit le plat du jour s'il existe et s'il est disponible. */
    public function definir(int $idPlat): void
    {
        $plat = model(PlatModel::class)->find($idPlat);

        if ($plat === null) {
            throw new RegleMetierException('Ce plat n’existe pas.', [
                'id_plat' => 'Choisissez un plat existant.',
            ]);
        }

        if (! $plat['disponible']) {
            throw new RegleMetierException(
                'Le ' . $plat['nom'] . ' est épuisé : il ne peut pas être plat du jour.',
                [],
                409
            );
        }

        model(PlatDuJourModel::class)->definirAujourdHui($idPlat);
    }
}
