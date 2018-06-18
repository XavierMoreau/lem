<?php

namespace LemaireBundle\Controller;

use LemaireBundle\Entity\Car;
use LemaireBundle\Entity\Marque;
use LemaireBundle\Entity\Modele;
use LemaireBundle\Entity\Energie;
use LemaireBundle\Entity\Type;
use LemaireBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Car controller.
 *
 * @Route("car")
 */
class CarController extends Controller
{
    /**
     * Lists all car entities.
     *
     * @Route("/", name="car_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cars = $em->getRepository('LemaireBundle:Car')->findAll();

        return $this->render('car/index.html.twig', array(
            'cars' => $cars,
        ));
    }

    /**
     * Creates a new car entity.
     *
     * @Route("/new", name="car_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        $car = new Car();
        $em = $this->getDoctrine()->getManager();
        
     
        
        if ($_POST) {
            
                                                           echo "<pre>";
                var_dump($_FILES);
                
                
                echo "</pre>";
                die;
            $form = $_POST["lemairebundle_car"];
            
            if ($form['new_marque'] !== ""){
                $marque = new Marque();
                $marque->setName(strtoupper($form['new_marque']));
                $em->persist($marque);
                $em->flush();
            }elseif ($form['marque'] !== ""){
                $getMarque = $em->getRepository('LemaireBundle:Marque')->findById($form['marque']);
                $marque = $getMarque[0];                
            }
            
            if ($form['new_modele'] !== ""){
                $modele = new Modele();
                $modele->setMarque($marque);
                $modele->setName(strtoupper($form['new_modele']));
                $em->persist($modele);
                $em->flush();
            }elseif ($form['modele'] !== ""){
                $getModele = $em->getRepository('LemaireBundle:Modele')->findById($form['modele']);
                $modele = $getModele[0];
            }
            
            if ($form['new_energie'] !== ""){
                $energie = new Energie();
                $energie->setName(strtoupper($form['new_energie']));
                $em->persist($energie);
                $em->flush();
            }elseif ($form['energie'] !== ""){
                $getEnergie = $em->getRepository('LemaireBundle:Energie')->findById($form['energie']);
                $energie = $getEnergie[0];
            }else{
                $energie = null;
            }

            if ($form['new_type'] !== ""){
                $type = new Type();
                $type->setName(strtoupper($form['new_type']));
                $em->persist($type);
                $em->flush();
            }elseif ($form['type'] !== ""){
                $getType = $em->getRepository('LemaireBundle:Type')->findById($form['type']);
                $type = $getType[0];
            }else{
                $type = null;
            }
            
            
            $car->setModele($modele);
            $car->setEnergie($energie);
            $car->setType($type);
            $car->setSerie($form['serie']);
            $car->setMotorisation($form['motorisation']);
            $car->setCvfiscaux($form['cvfiscaux']);
            $car->setAnnee($form['annee']);
            $car->setKms($form['kms']);
            $car->setCouleur($form['couleur']);
            $car->setBoitevitesse($form['boitevitesse']);
            $car->setPortes($form['portes']);
            $car->setPrixdestock($form['prixdestock']);
            $car->setPrixgarantie($form['prixgarantie']);
            
            
//            $car->setDate(date("Y-m-d H:i:s"));
            
            if (isset($form['promotion'])){
                $car->setPromotion($form['promotion']);
            }else{
                $car->setPromotion(0);
            }
                        
            if (isset($form['active'])){
                $car->setActive($form['active']);
            }else{
                $car->setActive(0);
            }
                        
            if (isset($form['vendu'])){
                $car->setVendu($form['vendu']);
            }else{
                $car->setVendu(0);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();
            
            $ref = $marque->getName() . "_" . $modele->getName() . "_" . $form['motorisation'] . "_" . $car->getId();
            $car->setRef($ref);
            $em->persist($car);
            $em->flush();
             
            foreach($form['images'] as $key => $imgUpld){
                $image = new Image();
                
                                            echo "<pre>";
                var_dump($imgUpld);
                
                
                echo "</pre>";

                
                $imgUpldName = new \SplFileInfo($imgUpld);
                $extension = $imgUpldName->getExtension();
                $imageName = $car->getModele()->getMarque()->getName() . "_" .$car->getModele()->getName() . "_" . date('Ymd') . "_" . $car->getId() . "_" . $key . ".". $extension;
                $imageNameSmall = $car->getModele()->getMarque()->getName() . "_" . $car->getModele()->getName() . "_" . date('Ymd') . "_" . $car->getId() . "_" . $key . "_small.". $extension;
                
                                                          echo "<pre>";
                var_dump($imgUpldName);
                var_dump($extension);
                var_dump($imageName);
                var_dump($imageNameSmall);
                echo "</pre>";

                $path = __DIR__.'/../../../web/img/cars';
                $image->setPath($path);
                $image->setPathsmall($path);
                
                                                          echo "<pre>";
                var_dump($path);
                echo "</pre>";
                die;
                $imgUpld->move($path, $imgUpld);
                
                
                
                $imageSmall = $this->resizeImage($imgUpld, "150", "200");
                $imageBig = $this->resizeImage($imgUpld, "300", "400");

                $image->setName($imageName);
                $image->setNamesmall($imageNameSmall);
                
                $imageSmall->move($path, $imageNameSmall);
                $imageBig->move($path, $imageName);
                $image->setCar($car);
                

                $em->persist($image);
                $em->flush();
            }
            
            
            
            
            
            
            
            
            
            return $this->redirectToRoute('car_show', array('id' => $car->getId()));
        }
        

        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $modeles = $em->getRepository('LemaireBundle:Modele')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        $options = $em->getRepository('LemaireBundle:Options')->findAll();
        $types = $em->getRepository('LemaireBundle:Type')->findAll();
        
        
        return $this->render('car/new.html.twig', array(
            'marques' => $marques,
            'modeles' => $modeles,
            'energies' => $energies,
            'options' => $options,
            'types' => $types,
            'car' => $car,
//            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a car entity.
     *
     * @Route("/{id}", name="car_show")
     * @Method("GET")
     */
    public function showAction(Car $car)
    {
        $deleteForm = $this->createDeleteForm($car);

        return $this->render('car/show.html.twig', array(
            'car' => $car,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing car entity.
     *
     * @Route("/{id}/edit", name="car_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Car $car)
    {
        $deleteForm = $this->createDeleteForm($car);
        $editForm = $this->createForm('LemaireBundle\Form\CarType', $car);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('car_edit', array('id' => $car->getId()));
        }

        return $this->render('car/edit.html.twig', array(
            'car' => $car,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a car entity.
     *
     * @Route("/{id}", name="car_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Car $car)
    {
        $form = $this->createDeleteForm($car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($car);
            $em->flush();
        }

        return $this->redirectToRoute('car_index');
    }

    /**
     * Creates a form to delete a car entity.
     *
     * @param Car $car The car entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Car $car)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('car_delete', array('id' => $car->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    
    function resizeImage($filename, $newwidth, $newheight){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);    
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagejpeg($thumb);
}
    
    
}
