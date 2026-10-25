<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatDuJourModel extends Model
{
    protected $table         = 'plat_du_jour';
    protected $primaryKey    = 'date_jour';
    protected $returnType    = 'array';
    protected $allowedFields = ['date_jour', 'id_plat'];

    /** Renvoie le plat choisi pour aujourd'hui, ou null. */
    public function lireAujourdHui(): ?array
    {
        $sql = 'SELECT pdj.date_jour, pdj.id_plat, p.nom, p.prix, p.description
                FROM plat_du_jour pdj
                JOIN plat p ON p.id = pdj.id_plat
                WHERE pdj.date_jour = CURRENT_DATE';

        return $this->db->query($sql)->getRowArray() ?: null;
    }

    /** Renvoie les plats du jour des sept derniers jours. */
    public function historique(): array
    {
        $sql = 'SELECT pdj.date_jour, pdj.id_plat, p.nom, p.prix, p.description
                FROM plat_du_jour pdj
                JOIN plat p ON p.id = pdj.id_plat
                WHERE pdj.date_jour BETWEEN CURRENT_DATE - 6 AND CURRENT_DATE
                ORDER BY pdj.date_jour DESC';

        return $this->db->query($sql)->getResultArray();
    }

    /** Choisit ou remplace le plat du jour. */
    public function definirAujourdHui(int $idPlat): void
    {
        $sql = 'INSERT INTO plat_du_jour (date_jour, id_plat)
                VALUES (CURRENT_DATE, ?)
                ON CONFLICT (date_jour)
                DO UPDATE SET id_plat = EXCLUDED.id_plat';

        $this->db->query($sql, [$idPlat]);
    }
}
