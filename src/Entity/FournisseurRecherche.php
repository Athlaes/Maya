<?php

namespace App\Entity;

class FournisseurRecherche
{
    private $nom;

    private $email;

    private $dateEnRelation;

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of dateEnRelation
     */ 
    public function getDateEnRelation()
    {
        return $this->dateEnRelation;
    }

    /**
     * Set the value of dateEnRelation
     *
     * @return  self
     */ 
    public function setDateEnRelation($dateEnRelation)
    {
        $this->dateEnRelation = $dateEnRelation;

        return $this;
    }
}