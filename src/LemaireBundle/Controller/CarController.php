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
            
            $ref = $marque->getName() . "_" . $modele->getName() . "_" . $form['motorisation'] . "_N" . $car->getId();
            $car->setRef($ref);
            $em->persist($car);
            $em->flush();
            
            $files = $this->reArrayFiles($_FILES['my_upload']);
            foreach($files as $key => $file){
                
                $image = $this->saveImage($file, $car, $key);
                if ($key == 0){
                    $image->setMain(true);
                } 
                $em = $this->getDoctrine()->getManager();
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
        $carsautres = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true));
        
        $photosautres=[];
        foreach ($carsautres as $carautre){
             $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $carautre->getId()));
             
//        echo '<pre>';
////        var_dump($cars);
//        var_dump($photos);
//        echo '</pre>';
        
        $photosautres[$carautre->getId()] = $photo;
        }

        return $this->render('car/show.html.twig', array(
            'car' => $car,
            'photos' => $photos,
            'cars' => $carsautres,
            'photosautres' => $photosautres,
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

        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);


        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                           
            $form = $_POST["lemairebundle_car"];
            
            
//                                   echo "<pre>";
//                    var_dump($form);
//                echo "</pre>";
//                die;
            
            if ($form['new_marque'] !== ""){
                $marque = new Marque();
                $marque->setName(strtoupper($form['new_marque']));
                $em->persist($marque);
                $em->flush();
            }elseif ($form['marque'] !== ""){
                if ($car->getModele()->getMarque()->getId() !== $form['marque']){
                    $getMarque = $em->getRepository('LemaireBundle:Marque')->findById($form['marque']);
                    $marque = $getMarque[0];
                }
            }
            
            if ($form['new_modele'] !== ""){
                $modele = new Modele();
                $modele->setMarque($marque);
                $modele->setName(strtoupper($form['new_modele']));
                $em->persist($modele);
                $em->flush();
            }elseif ($form['modele'] !== ""){
                if ($car->getModele()->getId() !== $form['modele']){
                    $getModele = $em->getRepository('LemaireBundle:Modele')->findById($form['modele']);
                    $modele = $getModele[0];
                }
            }
            
            if ($form['new_energie'] !== ""){
                $energie = new Energie();
                $energie->setName(strtoupper($form['new_energie']));
                $em->persist($energie);
                $em->flush();
            }elseif ($form['energie'] !== ""){
                if ($car->getEnergie()->getId() !== $form['energie']){
                    $getEnergie = $em->getRepository('LemaireBundle:Energie')->findById($form['energie']);
                    $energie = $getEnergie[0];
                }    
            }else{
                $energie = null;
            }

            if ($form['new_type'] !== ""){
                $type = new Type();
                $type->setName(strtoupper($form['new_type']));
                $em->persist($type);
                $em->flush();
            }elseif ($form['type'] !== ""){
                if ($car->getType()->getId() !== $form['type']){
                    $getType = $em->getRepository('LemaireBundle:Type')->findById($form['type']);
                    $type = $getType[0];
                }
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
            
            $ref = $marque->getName() . "_" . $modele->getName() . "_" . $form['motorisation'] . "_N" . $car->getId();
            $car->setRef($ref);
            $em->persist($car);
            $em->flush();
             
            foreach ($photos as $photo){
                $idPhoto = strval($photo->getId());
                
                if ($idPhoto === $form['mainphoto']){
                    $photo->setMain(true);
                }else{
                    $photo->setMain(false);
                }
                    $em->persist($photo);
                    $em->flush();
            }

            if ($_FILES['my_upload']['name'][0] !== ""){
                $files = $this->reArrayFiles($_FILES['my_upload']);
                
                foreach($files as $key => $file){
                    $image = $this->saveImage($file, $car, $key);

                    $em->persist($image);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('car_edit', array('id' => $car->getId()));
        }

        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $modeles = $em->getRepository('LemaireBundle:Modele')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        $options = $em->getRepository('LemaireBundle:Options')->findAll();
        $types = $em->getRepository('LemaireBundle:Type')->findAll();
        
        
        return $this->render('car/edit.html.twig', array(
            'marques' => $marques,
            'modeles' => $modeles,
            'energies' => $energies,
            'options' => $options,
            'types' => $types,
            'car' => $car,
            'photos'=> $photos,
            'delete_form' => $deleteForm->createView(),
        ));
    }
        
        
        
    public function saveImage($file, $car, $key){
            $image = new Image();
                
            $fileName = new \SplFileInfo($file['name']);
            $extension = $fileName->getExtension();
            $imageName = $car->getId() . "_" . date("YmdHis") . "_" . $key . ".". $extension;
            $imageNameSmall = $car->getId() . "_" . date("YmdHis"). "_" . $key . "_small.". $extension;

            $carFolder = $car->getModele()->getMarque()->getName() . '_' . $car->getModele()->getName() . '_N' . $car->getId();
            $path = '../web/img/cars/'.$carFolder;
            $pathBig = $path.'/big';
            $pathSmall = $path.'/thumbs';

            if (!file_exists($path)){
              mkdir($path, 0777);
            }

            if (!file_exists($pathBig)){
              mkdir($pathBig, 0777);
            }

            if (!file_exists($pathSmall)){
              mkdir($pathSmall, 0777);
            }

            $completeNameOriginal = $path .'/'. $imageName;
            $completeNameBig = $pathBig .'/'. $imageName;
            $completeNameSmall = $pathSmall .'/'. $imageNameSmall;

            move_uploaded_file($file['tmp_name'], $completeNameOriginal);

            $this->create_square_image($completeNameOriginal, $completeNameBig,750);
            $this->create_square_image($completeNameOriginal, $completeNameSmall,200);

            unlink($completeNameOriginal);

            $image->setPath($carFolder.'/big/');
            $image->setPathsmall($carFolder.'/thumbs/');
            $image->setName($imageName);
            $image->setNamesmall($imageNameSmall);

            $image->setCar($car);
            
            return $image;
            
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
    
     
    function create_square_image($original_file, $destination_file=NULL, $square_size = 96){
		
		if(isset($destination_file) and $destination_file!=NULL){
			if(!is_writable($destination_file)){
                            
				echo "<p style='color:#FF0000'>Oops, il y a un problème à l'enregistrement des images.</p>"; 
			}
		}
		
		// get width and height of original image
		$imagedata = getimagesize($original_file);
		$original_width = $imagedata[0];	
		$original_height = $imagedata[1];

		if($original_width > $original_height){
			$new_height = $square_size;
			$new_width = $new_height*($original_width/$original_height);
                       
		}
		if($original_height > $original_width){
			$new_width = $square_size;
			$new_height = ($new_width*$original_height/$original_width);
                         
		}
		if($original_height == $original_width){
			$new_width = $square_size;
			$new_height = $square_size;
		}
		
		$new_width = round($new_width);
		$new_height = round($new_height);
		
		// load the image
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			$original_image = imagecreatefromjpeg($original_file);
		}
		if(substr_count(strtolower($original_file), ".gif")){
			$original_image = imagecreatefromgif($original_file);
		}
		if(substr_count(strtolower($original_file), ".png")){
			$original_image = imagecreatefrompng($original_file);
		}
		
		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		$square_image = imagecreatetruecolor($square_size, $square_size);
                
                imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		
                
                
		if($new_width>$new_height){
			$difference = $new_width-$new_height;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $square_size+$difference, $square_size, $new_width, $new_height);
		}
		if($new_height>$new_width){
			$difference = $new_height-$new_width;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
		}
		if($new_height == $new_width){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
		}
		

		// if no destination file was given then display a png		
		if(!$destination_file){
			imagepng($square_image,NULL,9);
		}
		
		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			imagejpeg($square_image,$destination_file,100);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($square_image,$destination_file);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($square_image,$destination_file,9);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);

	}
    
    
    
}
