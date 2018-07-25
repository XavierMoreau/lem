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
            
            $options = '';
            if (isset($form['options'])){
                foreach ($form['options'] as $option){
                 $getOption = $em->getRepository('LemaireBundle:Options')->findById($option);
                 $options .= $getOption[0]->getName() . ', ';
                }
            }
            
            if (isset($form['option supp'])){
                foreach ($form['option supp'] as $option_suppl){
                
                 $options .= $option_suppl . ', ';
                }
            }
            
            $car->setOptions($options);
            
            
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
            
            if (isset($form['newpics'])){ 
                $files = $this->reArrayFiles($_FILES['my_upload']);
            
                foreach($form['newpics'] as $pic){
                    
                    foreach($files as $key => $file){

                    if ($pic['name'] === $file['name']){
                        
                        $image = $this->saveImage($file, $car, $key);
                        
                        
                        if (isset($pic['main'])){
                            $image->setMain(true);
                        }else{
                            $image->setMain(false);
                        }

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($image);
                        $em->flush();
                        }
                    }
                }   
               
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
        
        $optionArray = explode(", ",$car->getOptions());
        $car->setOptions($optionArray);
        
        $deleteForm = $this->createDeleteForm($car);
        
        $em = $this->getDoctrine()->getManager();
        
        $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);
        $carsautres = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true));
        
        $photosautres=[];
        foreach ($carsautres as $carautre){
             $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $carautre->getId()));
             
//        echo '<pre>';
////        var_dump($cars);
//        var_dump($photo);
//        echo '</pre>';
//        die;
        if (isset($photo[0])){
            array_push($photosautres, $photo[0]);
        }
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
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $modeles = $em->getRepository('LemaireBundle:Modele')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        $types = $em->getRepository('LemaireBundle:Type')->findAll();
        
        //Pour les options, on récupère la liste d'options de base        
        $optionsBase = $em->getRepository('LemaireBundle:Options')->findAll();
        // on stocke uniquement les noms de ces options dans un tableau
        $options = [];
        foreach ($optionsBase as $option){
            array_push($options,$option->getName());
        }

        // On stocke les options du véhicule dans un tableau
        $caroptions = explode(", ",$car->getOptions());
        
        // On crée deux tableaux : options défault(a comparer avec les options de base) et options supplementaires
        $optionsDefault = [];
        $optionsSuppl =[];
        // Si l'option correspond a une option de base alors on la met dans le tableau défaut sinon tableau Options supplémentaires
        foreach ($caroptions as $caroption){               
            if (in_array($caroption, $options)){
                array_push($optionsDefault, $caroption);
            }else{
                if (strlen($caroption)>0)
                array_push($optionsSuppl, $caroption);          
            }
        }
        
        // On resette l'objet CAR avec les options classées
        $optionArray = [];
        $optionArray['default'] = $optionsDefault;
        $optionArray['suppl'] = $optionsSuppl;
        
        $car->setOptions($optionArray);
        

        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                           
            $form = $_POST["lemairebundle_car"];
            
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
            
            $options = '';
            if (isset($form['options'])){
                foreach ($form['options'] as $option){
                 $getOption = $em->getRepository('LemaireBundle:Options')->findById($option);
                 $options .= $getOption[0]->getName() . ', ';
                }
            }
            
            if (isset($form['option supp'])){
                foreach ($form['option supp'] as $option_suppl){
                
                 $options .= $option_suppl . ', ';
                }
            }
            
            $car->setOptions($options);
   
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();
            
            $ref = $marque->getName() . "_" . $modele->getName() . "_" . $form['motorisation'] . "_N" . $car->getId();
            $car->setRef($ref);
            $em->persist($car);
            $em->flush();
            
            $existpics=[];
            if (isset($form['existpics'])){

                foreach($form['existpics'] as $pic){
                    array_push($existpics, $pic['id']);
                }
                
                    echo "<pre>";
                    var_dump($form['existpics']);
//                    var_dump($existpics);
//                    var_dump($_FILES['my_upload']);
                   foreach ($photos as $photo){
                    var_dump(strval($photo->getId()));
                    var_dump($photo->getMain());
                   
                   }
                echo "</pre>";

                foreach($form['existpics'] as $pic){
                    
                    foreach ($photos as $photo){
                        
                        
                        $idPhoto = strval($photo->getId());
                        if ($pic['id'] === $idPhoto){
                            if (isset($pic['main'])){
                               if ($photo->getMain() === false){
                                $photo->setMain(true);
                                $em->persist($photo);
                                $em->flush();
                               }
                            }else{
                                if ($photo->getMain() === true){
                                $photo->setMain(false);
                                $em->persist($photo);
                                $em->flush();
                               }
                            }
                        }                  

                        if (!(in_array($idPhoto, $existpics))){
                            $em->remove($photo);
                            $em->flush();
                        }                      
                    }
                }
                
            }

            if (isset($form['newpics'])){ 
                $files = $this->reArrayFiles($_FILES['my_upload']);
            
                foreach($form['newpics'] as $pic){
                    
                    foreach($files as $key => $file){

                    if ($pic['name'] === $file['name']){
                        
                        $image = $this->saveImage($file, $car, $key);
                        
                        if (isset($pic['main'])){
                            $image->setMain(true);
                        }else{
                            $image->setMain(false);
                        }
                        $em->persist($image);
                        $em->flush();
                        }
                    }
                }   
               
            }
            return $this->redirectToRoute('car_show', array('id' => $car->getId()));
        }        
        
        return $this->render('car/edit.html.twig', array(
            'marques' => $marques,
            'modeles' => $modeles,
            'energies' => $energies,
            'options' => $optionsBase,
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
                            
//				echo "<p style='color:#FF0000'>Oops, il y a un problème à l'enregistrement des images.</p>"; 
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
                
                imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		
                               
                $rect =  ['x' => 0, 'y' => 0, 'width' => $square_size*1.25, 'height' => $square_size];
                
                $image_cropped = imagecrop($smaller_image, $rect);
              

		// if no destination file was given then display a png		
		if(!$destination_file){
			imagepng($image_cropped,NULL,9);
		}
		            
                // save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			imagejpeg($image_cropped,$destination_file,100);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($image_cropped,$destination_file);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($image_cropped,$destination_file,9);
		}

		imagedestroy($original_image);
		imagedestroy($image_cropped);

	}
    
    
    
}
