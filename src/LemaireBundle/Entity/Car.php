<?php

namespace LemaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Table(name="car")
 * @ORM\Entity(repositoryClass="LemaireBundle\Repository\CarRepository")
 */
class Car
{
     public function __construct()
    {
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->date = new \Datetime();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ref", type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    
      /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesold", type="datetime")
     */
    private $dateSold;


    /**
     * @var string|null
     *
     * @ORM\Column(name="serie", type="string", length=255, nullable=true)
     */
    private $serie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="motorisation", type="string", length=255, nullable=true)
     */
    private $motorisation;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cvfiscaux", type="integer", nullable=true)
     */
    private $cvfiscaux;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="annee", type="bigint", nullable=true)
     */
    private $annee;

    /**
     * @var int|null
     *
     * @ORM\Column(name="kms", type="bigint", nullable=true)
     */
    private $kms;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prixdestock", type="decimal", precision=6, scale=0, nullable=true)
     */
    private $prixdestock;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="prixgarantie", type="decimal", precision=6, scale=0, nullable=true)
     */
    private $prixgarantie;
    
    /**
     * @var int|null
     * @Assert\Type(
     *     type="integer",
     *     message="La temps de garantie doit être un nombre entier."
     * )
     * @ORM\Column(name="tempsgarantie", type="integer", nullable=true)
     */
    private $tempsgarantie;

    /**
     * @var bool
     *
     * @ORM\Column(name="vendu", type="boolean")
     */
    private $vendu;

    /**
     * @var bool
     *
     * @ORM\Column(name="promotion", type="boolean")
     */
    private $promotion;
    
        /**
     * @var bool
     *
     * @ORM\Column(name="centrale", type="boolean")
     */
    private $centrale;
    
         /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_centrale", type="datetime")
     */
    private $dateCentrale;

    
             /**
     * @var bool
     *
     * @ORM\Column(name="status_centrale", type="string")
     */
    private $statusCentrale;
    
                 /**
     * @var bool
     *
     * @ORM\Column(name="comment_centrale", type="string")
     */
    private $commentCentrale;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string|null
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @var int|null
     *
     * @ORM\Column(name="boitevitesse", type="string", length=255, nullable=true)
     */
    private $boitevitesse;

    /**
     * @var int|null
     *
     * @ORM\Column(name="portes", type="integer", nullable=true)
     */
    private $portes;
    
    
      /**
     * @var int|null
     *
     * @ORM\Column(name="places", type="integer", nullable=true)
     */
    private $places;

    

    /**
   * @ORM\ManyToOne(targetEntity="LemaireBundle\Entity\Modele")
   * @ORM\JoinColumn(nullable=true)
   */
    private $modele;

    /**
   * @ORM\ManyToOne(targetEntity="LemaireBundle\Entity\Energie")
   * @ORM\JoinColumn(nullable=true)
   */
    private $energie;

    /**
   * @ORM\ManyToOne(targetEntity="LemaireBundle\Entity\Type")
   * @ORM\JoinColumn(nullable=true)
   */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="options", type="string", length=5000, nullable=true)
     */
    private $options;
    
    



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ref.
     *
     * @param string|null $ref
     *
     * @return Car
     */
    public function setRef($ref = null)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref.
     *
     * @return string|null
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Car
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    
    /**
     * Set dateSold.
     *
     * @param \DateTime $dateSold
     *
     * @return Car
     */
    public function setDateSold($dateSold)
    {
        $this->dateSold = $dateSold;

        return $this;
    }
    
    /**
     * Get dateSold.
     *
     * @return \DateTime
     */
    public function getDateSold()
    {
        return $this->dateSold;
    }

    /**
     * Set serie.
     *
     * @param string|null $serie
     *
     * @return Car
     */
    public function setSerie($serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie.
     *
     * @return string|null
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set motorisation.
     *
     * @param string|null $motorisation
     *
     * @return Car
     */
    public function setMotorisation($motorisation = null)
    {
        $this->motorisation = $motorisation;

        return $this;
    }

    /**
     * Get motorisation.
     *
     * @return string|null
     */
    public function getMotorisation()
    {
        return $this->motorisation;
    }

    /**
     * Set cvfiscaux.
     *
     * @param int|null $cvfiscaux
     *
     * @return Car
     */
    public function setCvfiscaux($cvfiscaux = null)
    {
        $this->cvfiscaux = $cvfiscaux;

        return $this;
    }

    /**
     * Get cvfiscaux.
     *
     * @return int|null
     */
    public function getCvfiscaux()
    {
        return $this->cvfiscaux;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Car
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set annee.
     *
     * @param int|null $annee
     *
     * @return Car
     */
    public function setAnnee($annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee.
     *
     * @return int|null
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set kms.
     *
     * @param int|null $kms
     *
     * @return Car
     */
    public function setKms($kms = null)
    {
        $this->kms = $kms;

        return $this;
    }

    /**
     * Get kms.
     *
     * @return int|null
     */
    public function getKms()
    {
        return $this->kms;
    }

    /**
     * Set prixdestock.
     *
     * @param string|null $prixdestock
     *
     * @return Car
     */
    public function setPrixdestock($prixdestock = null)
    {
        $this->prixdestock = $prixdestock;

        return $this;
    }

    /**
     * Get prixdestock.
     *
     * @return string|null
     */
    public function getPrixdestock()
    {
        return $this->prixdestock;
    }

    /**
     * Set prixgarantie.
     *
     * @param string|null $prixgarantie
     *
     * @return Car
     */
    public function setPrixgarantie($prixgarantie = null)
    {
        $this->prixgarantie = $prixgarantie;

        return $this;
    }

    /**
     * Get prixgarantie.
     *
     * @return string|null
     */
    public function getPrixgarantie()
    {
        return $this->prixgarantie;
    }

    /**
     * Set vendu.
     *
     * @param bool $vendu
     *
     * @return Car
     */
    public function setVendu($vendu)
    {
        $this->vendu = $vendu;

        return $this;
    }

    /**
     * Get vendu.
     *
     * @return bool
     */
    public function getVendu()
    {
        return $this->vendu;
    }

    /**
     * Set promotion.
     *
     * @param bool $promotion
     *
     * @return Car
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get centrale.
     *
     * @return bool
     */
    public function getCentrale()
    {
        return $this->centrale;
    }
    
        /**
     * Set centrale.
     *
     * @param bool $centrale
     *
     * @return Car
     */
    public function setCentrale($centrale)
    {
        $this->centrale = $centrale;

        return $this;
    }
    
    function getDateCentrale() {
        return $this->dateCentrale;
    }

    function getStatusCentrale() {
        return $this->statusCentrale;
    }

    function getCommentCentrale() {
        return $this->commentCentrale;
    }

    function setDateCentrale($dateCentrale) {
        $this->dateCentrale = $dateCentrale;
    }

    function setStatusCentrale($statusCentrale) {
        $this->statusCentrale = $statusCentrale;
    }

    function setCommentCentrale($commentCentrale) {
        $this->commentCentrale = $commentCentrale;
    }

        
    

    /**
     * Get promotion.
     *
     * @return bool
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
    
    

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Car
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set couleur.
     *
     * @param string|null $couleur
     *
     * @return Car
     */
    public function setCouleur($couleur = null)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur.
     *
     * @return string|null
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set boitevitesse.
     *
     * @param int|null $boitevitesse
     *
     * @return Car
     */
    public function setBoitevitesse($boitevitesse = null)
    {
        $this->boitevitesse = $boitevitesse;

        return $this;
    }

    /**
     * Get boitevitesse.
     *
     * @return int|null
     */
    public function getBoitevitesse()
    {
        return $this->boitevitesse;
    }

    /**
     * Set portes.
     *
     * @param int|null $portes
     *
     * @return Car
     */
    public function setPortes($portes = null)
    {
        $this->portes = $portes;

        return $this;
    }

    /**
     * Get portes.
     *
     * @return int|null
     */
    public function getPortes()
    {
        return $this->portes;
    }


    /**
     * Get places.
     *
     * @return int|null
     */    
    function getPlaces() {
        return $this->places;
    }
    
    /**
     * Set portes.
     *
     * @param int|null $places
     *
     * @return Car
     */

    function setPlaces($places) {
        $this->places = $places;
    }

        
    
    
    /**
     * Set modele.
     *
     * @param int $modele
     *
     * @return Car
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele.
     *
     * @return int
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set energie.
     *
     * @param int $energie
     *
     * @return Car
     */
    public function setEnergie($energie)
    {
        $this->energie = $energie;

        return $this;
    }

    /**
     * Get energie.
     *
     * @return int
     */
    public function getEnergie()
    {
        return $this->energie;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Car
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set options.
     *
     * @param array|null $options
     *
     * @return Car
     */
    public function setOptions($options = null)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options.
     *
     * @return array|null
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Get tempsgarantie
     *
     * @return int
     */
    function getTempsgarantie() {
        return $this->tempsgarantie;
    }
    
    /**
     * Set tempsgarantie.
     *
     * @param int $tempsgarantie
     *
     * @return Car
     */
    function setTempsgarantie($tempsgarantie) {
        $this->tempsgarantie = $tempsgarantie;
        return $this;
    }  
}
