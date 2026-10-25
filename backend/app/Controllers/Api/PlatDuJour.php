<?php

namespace App\Controllers\Api;

use App\Exceptions\RegleMetierException;
use App\Models\PlatDuJourModel;
use App\Services\PlatDuJourService;

class PlatDuJour extends ApiController
{
    /** GET /api/plat-du-jour */
    public function index()
    {
        return $this->respond(model(PlatDuJourModel::class)->lireAujourdHui());
    }

    /** PUT /api/plat-du-jour */
    public function definir()
    {
        $donnees = $this->donneesJson();
        $idPlat  = filter_var($donnees['id_plat'] ?? null, FILTER_VALIDATE_INT);

        if ($idPlat === false || $idPlat === null) {
            return $this->erreurValidation([
                'id_plat' => 'Choisissez un plat.',
            ]);
        }

        try {
            (new PlatDuJourService())->definir($idPlat);
        } catch (RegleMetierException $e) {
            return $this->erreurMetier($e);
        }

        return $this->respond(model(PlatDuJourModel::class)->lireAujourdHui());
    }

    /** GET /api/plat-du-jour/historique */
    public function historique()
    {
        return $this->respond(model(PlatDuJourModel::class)->historique());
    }
}
