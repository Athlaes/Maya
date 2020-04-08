<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class ProduitRecherche
{
    /**
     * @var string|null
     */
    private $libelle;
    
    /**
     * @var float|null
     */
    private $prixMini;

    /**
     * @var float|null
     */
    private $prixMaxi;

    /**
     * Get the value of prixMini
     * @return  float|null
     */ 
    public function getPrixMini()
    {
        return $this->prixMini;
    }

    /**
     * Set the value of prixMini
     * @param  float|null  $prixMini
     * @return  self
     */ 
    public function setPrixMini($prixMini)
    {
        $this->prixMini = $prixMini;

        return $this;
    }

    /**
     * Get the value of libelle
     *
     * @return  string|null
     */ 
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set the value of libelle
     *
     * @param  string|null  $libelle
     *
     * @return  self
     */ 
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get the value of prixMaxi
     *
     * @return  float|null
     */ 
    public function getPrixMaxi()
    {
        return $this->prixMaxi;
    }

    /**
     * Set the value of prixMaxi
     *
     * @param  float|null  $prixMaxi
     *
     * @return  self
     */ 
    public function setPrixMaxi($prixMaxi)
    {
        $this->prixMaxi = $prixMaxi;

        return $this;
    }
}
