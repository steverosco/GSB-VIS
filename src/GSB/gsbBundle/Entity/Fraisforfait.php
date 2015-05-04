<?php

namespace GSB\gsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fraisforfait
 *
 * @ORM\Table(name="FraisForfait")
 * @ORM\Entity(repositoryClass="GSB\gsbBundle\Entity\FraisForfaitRepository")
 */
class Fraisforfait
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $montant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fichefrais", mappedBy="idfraisforfait")
     */
    private $idvisiteur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idvisiteur = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Fraisforfait
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Fraisforfait
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add idvisiteur
     *
     * @param \GSB\gsbBundle\Entity\Fichefrais $idvisiteur
     * @return Fraisforfait
     */
    public function addIdvisiteur(\GSB\gsbBundle\Entity\Fichefrais $idvisiteur)
    {
        $this->idvisiteur[] = $idvisiteur;

        return $this;
    }

    /**
     * Remove idvisiteur
     *
     * @param \GSB\gsbBundle\Entity\Fichefrais $idvisiteur
     */
    public function removeIdvisiteur(\GSB\gsbBundle\Entity\Fichefrais $idvisiteur)
    {
        $this->idvisiteur->removeElement($idvisiteur);
    }

    /**
     * Get idvisiteur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdvisiteur()
    {
        return $this->idvisiteur;
    }
}
