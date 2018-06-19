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
        
     
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            

                
                $files = $this->reArrayFiles($_FILES['my_upload']);
                
                

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
            
            if ($form['cvfiscaux'] !== ""){
             $car->setCvfiscaux($form['cvfiscaux']);
            }else{
             $car->setCvfiscaux(0);
            }
            
            if ($form['annee'] !== ""){
             $car->setAnnee($form['annee']);
            }else{
             $car->setAnnee(0);
            }
            
             if ($form['kms'] !== ""){
             $car->setKms($form['kms']);
            }else{
             $car->setKms(0);
            }
            
             if ($form['portes'] !== ""){
             $car->setPortes($form['portes']);
            }else{
             $car->setPortes(0);
            }
            
             if ($form['prixdestock'] !== ""){
             $car->setPrixdestock($form['prixdestock']);
            }else{
             $car->setPrixdestock(0);
            }
            
             if ($form['prixgarantie'] !== ""){
             $car->setPrixgarantie($form['prixgarantie']);
            }else{
             $car->setPrixgarantie(0);
            }
            
            $car->setModele($modele);
            $car->setEnergie($energie);
            $car->setType($type);
            $car->setSerie($form['serie']);
            $car->setMotorisation($form['motorisation']);
            $car->setCouleur($form['couleur']);
            $car->setBoitevitesse($form['boitevitesse']);


            
            
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
            
            $ref = $marque->getName() . "_" . $modele->getName() . "_" . $form['motorisation'] . "_Num" . $car->getId();
            $car->setRef($ref);
            $em->persist($car);
            $em->flush();
             
            foreach($files as $key => $file){
                $image = new Image();

                
                $fileName = new \SplFileInfo($file['name']);
                $extension = $fileName->getExtension();
                $imageName = $car->getModele()->getMarque()->getName() . "_" .$car->getModele()->getName() . "_" . date('Y-m-d') . "_Num" . $car->getId() . "_" . $key . ".". $extension;
                $imageNameSmall = $car->getModele()->getMarque()->getName() . "_" . $car->getModele()->getName() . "_" . date('Y-m-d') . "_Num" . $car->getId() . "_" . $key . "_small.". $extension;
                

                $path = '../web/img/cars/';
                $image->setPath($path);
                $image->setPathsmall($path);
                

                
                $completeName = $path . $imageName;
                $completeNameSmall = $path . $imageNameSmall;

                $result = move_uploaded_file($file['tmp_name'], $completeName);
                
//                copy($completeName, $completeNameSmall);
                               
               
                list($x, $y) = getimagesize($completeName) ; 
                
                // horizontal rectangle
                if ($x > $y) {
                    $square = $y;              // $square: square side length
                    $offsetX = ($x - $y) / 2;  // x offset based on the rectangle
                    $offsetY = 0;              // y offset based on the rectangle
                }
                // vertical rectangle
                elseif ($y > $x) {
                    $square = $x;
                    $offsetX = 0;
                    $offsetY = ($y - $x) / 2;
                }
                // it's already a square
                else {
                    $square = $x;
                    $offsetX = $offsetY = 0;
                }
                
                $endSize = 100;
                $tn = imagecreatetruecolor($endSize, $endSize);
                $img = imagecreatefromjpeg($completeName);
                imagecopyresampled($tn, $img, 0, 0, $offsetX, $offsetY, $endSize, $endSize, $square, $square);

                imagejpeg($img,$completeNameSmall,100);
                
              

                $image->setName($imageName);
                $image->setNamesmall($imageNameSmall);
                
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
        
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);

        return $this->render('car/show.html.twig', array(
            'car' => $car,
            'photos' => $photos,
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
    
    
    
    
    
    function resizeImage($filename, $newwidth, $newheight)
    {
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
  
    function reArrayFiles($file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

    return $file_ary;
}
    
}
