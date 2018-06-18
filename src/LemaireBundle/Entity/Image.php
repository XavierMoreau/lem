<?php

namespace LemaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="LemaireBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=1064)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="namesmall", type="string", length=255)
     */
    private $namesmall;

    /**
     * @var string
     *
     * @ORM\Column(name="pathsmall", type="string", length=1064)
     */
    private $pathsmall;

    /**
   * @ORM\ManyToOne(targetEntity="LemaireBundle\Entity\Car")
   * @ORM\JoinColumn(nullable=false)
   */
    private $car;

    private $file;

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
     * Set path.
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set namesmall.
     *
     * @param string $namesmall
     *
     * @return Image
     */
    public function setNamesmall($namesmall)
    {
        $this->namesmall = $namesmall;

        return $this;
    }

    /**
     * Get namesmall.
     *
     * @return string
     */
    public function getNamesmall()
    {
        return $this->namesmall;
    }

    /**
     * Set pathsmall.
     *
     * @param string $pathsmall
     *
     * @return Image
     */
    public function setPathsmall($pathsmall)
    {
        $this->pathsmall = $pathsmall;

        return $this;
    }

    /**
     * Get pathsmall.
     *
     * @return string
     */
    public function getPathsmall()
    {
        return $this->pathsmall;
    }

    /**
     * Set car.
     *
     * @param int $car
     *
     * @return Image
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car.
     *
     * @return int
     */
    public function getCar()
    {
        return $this->car;
    }

    public function getFile()
    {
      return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
      $this->file = $file;
    }

      public function upload()
    {
      // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
      if (null === $this->file) {
        return;
      }

      // On récupère le nom original du fichier de l'internaute
      $name = $this->file->getClientOriginalName();

      // On déplace le fichier envoyé dans le répertoire de notre choix
      $this->file->move($this->getUploadRootDir(), $name);

     
    }

    public function getUploadDir()
    {
      // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
      return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    
}
