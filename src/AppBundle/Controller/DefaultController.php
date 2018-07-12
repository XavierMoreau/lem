<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use LemaireBundle\Entity\Car;
use LemaireBundle\Entity\Marque;
use LemaireBundle\Entity\Modele;
use LemaireBundle\Entity\Energie;
use LemaireBundle\Entity\Image;
use Symfony\Component\Validator\Constraints\DateTime;
use LemaireBundle\Controller\CarController;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        
        $this->convertCarAction($request);
        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig');
            
        
    }
    
    

    public function convertCarAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
//
//        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true));
//        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
//        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
//        $photos = $em->getRepository('LemaireBundle:Image')->findAll();
        
        // Saisie des Modèles et marques (avoir les marques)
        
//        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
//        
//        $carsArray = [];
//        foreach ($marques as $key => $marque){
//            
//            $CARS_BY_MARQUES_QUERY = 'SELECT designation FROM lemaire_autos_vehicules WHERE marque = "'.$marque->getName().'";';    
//            $carsbymarques_statement = $em->getConnection()->prepare($CARS_BY_MARQUES_QUERY);
//            $carsbymarques_statement->execute();
//            $carsbymarques_results = $carsbymarques_statement->fetchAll();
//            
//            $regex_designation = '/^([A-Za-z0-9\s+-]*)\s([0-9].[0-9]\s?L?[\sa-zA-Z0-9]*),\s?([0-9]*)cv/si';
//                       
//            $carsArray[$key]['marque'] = $marque->getId();
//            
//            $modeleArray = [];
//            
//            foreach ($carsbymarques_results as $carbymarque){
//                preg_match_all($regex_designation, $carbymarque["designation"], $match_designation, PREG_SET_ORDER, 0);
//                
//                if (!(in_array($match_designation[0][1], $modeleArray))){
//
//                    array_push($modeleArray, $match_designation[0][1]);
//                    
//                    $modele = new \LemaireBundle\Entity\Modele;
//                    $modele->setMarque($marque);
//                    $modele->setName($match_designation[0][1]);
//                    $em->persist($modele);
//                    $em->flush();
//                }
//            }
//            
//            $carsArray[$key]['Modeles'] = $modeleArray;    
//        }

                
        $CARS_QUERY = 'SELECT * FROM lemaire_autos_vehicules;';    
        $cars_statement = $em->getConnection()->prepare($CARS_QUERY);
        $cars_statement->execute();
        $cars_results = $cars_statement->fetchAll();
        
            
        $regex_designation = '/^([A-Za-z0-9\s+-]*)\s([0-9].[0-9]\s?L?[\sa-zA-Z0-9]*),\s?([0-9]*)cv/si';
        $regex_descr = '/Prix garantie 1 an : ([0-9]*) euros/si';
        
//        foreach ($cars_results as $result){
//            preg_match_all($regex_designation, $result["designation"], $match_designation, PREG_SET_ORDER, 0);
//        
//            echo "<pre>";
//            var_dump($match_designation);
//            echo "</pre>";
//        }
//        
//        die;
        
        foreach ($cars_results as $result){
            
          $car = new Car;
          
        preg_match_all($regex_designation, $result["designation"], $match_designation, PREG_SET_ORDER, 0);
             
        // Gestion de la Marque
        // Récupération de la marque du véhicule existant
        $marqueResult = strtoupper($result["marque"]);
        // Récupération des marques existantes dans la base
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        
        // Stock des marques existantes dans un tableau
        $marquesArray=[];        
        foreach ($marques as $marque){
            array_push($marquesArray, $marque->getName());
        }
        // comparaison de la marque du véhicule avec la liste des marques existantes
        if (!(in_array($marqueResult, $marquesArray))){
            // Si NON existante : on la créée
                $newmarque = new Marque();
                $newmarque->setName($marqueResult);
                $em->persist($newmarque);
                $em->flush();
                
        }else{
             $getMarque = $em->getRepository('LemaireBundle:Marque')->findByName($marqueResult);
             $newmarque = $getMarque[0];           
        }
        
        //Gestion du Modele
        //Recupération du modèle du véhicule existant
        $modeleResult = strtoupper($match_designation[0][1]);
        // Récupération des modèles existants dans la base
        $modeles = $em->getRepository('LemaireBundle:Modele')->findByMarque($newmarque);
         // Stock des modeles existantes dans un tableau
        $modelesArray=[];        
        foreach ($modeles as $modele){
            array_push($modelesArray, $modele->getName());
        }
        // comparaison de la marque du véhicule avec la liste des marques existantes
        if (!(in_array($modeleResult, $modelesArray))){
            // Si NON existante : on la créée
                $newmodele = new Modele();
                $newmodele->setMarque($newmarque);
                $newmodele->setName($modeleResult);
                $em->persist($newmodele);
                $em->flush();
        }else{
            $getModele = $em->getRepository('LemaireBundle:Modele')->findByName($modeleResult);
            $newmodele = $getModele[0];
        }
        
        $car->setModele($newmodele);
        
        
        // Gestion de l'Energie
        // Récupération de l'energie du véhicule existant
        $energieResult = strtolower($result["energie"]);
        // Récupération des energies existantes dans la base
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        
        // Stock des energies existantes dans un tableau
        $energiesArray=[];        
        foreach ($energies as $energie){
            array_push($energiesArray, strtolower($energie->getName()));
        }
        // comparaison de l'energie du véhicule avec la liste des marques existantes
        if (!(in_array($energieResult, $energiesArray))){
            // Si NON existante : on la créée
                $newenergie = new Energie();
                $newenergie->setName(ucfirst($energieResult));
                $em->persist($newenergie);
                $em->flush();
                
        }else{
             $getEnergie = $em->getRepository('LemaireBundle:Energie')->findByName($energieResult);
             $newenergie = $getEnergie[0];           
        }
        
        $car->setEnergie($newenergie);
        

            
        $motorisation = $match_designation[0][2];
        $cvFiscaux = $match_designation[0][3];
        $car->setMotorisation($motorisation);
        $car->setCvfiscaux($cvFiscaux);
        $prix = str_replace('.00', '', $result['prix']);
        $car->setAnnee($result['annee']);
        $car->setKms($result['km']);
        $car->setPrixdestock($prix);
        $car->setVendu($result['vendu']);
        $car->setPromotion(0);

        
        
        $date = new \DateTime($result['datepost']);
       
        $car->setDate($date);
        
        
        $car->setActive($result['statut']);
        
        if ($prix === '0' || $result['photo1'] === ""){
            $car->setActive(0);
        }
        
        //Prix Garantie 
        preg_match_all($regex_descr, $result["descr"], $match_prixgarantie, PREG_SET_ORDER, 0);
        
        if (isset($match_prixgarantie[0][1]) && $match_prixgarantie[0][1] != "0000"){
            $car->setPrixgarantie($match_prixgarantie[0][1]);
        }
        
        $options = str_replace('...', '', $result['options']);
        
        $optionArray = explode(", ",$options);
        $optionsfinal = "";
        
        foreach ($optionArray as $option){
            $option2 = str_replace("^\s*", '', $option);

            $option3 = ucfirst($option2);

            $optionsfinal .= $option3 . ', ';
        }
        
        $car->setOptions($optionsfinal);
        
        $em->persist($car);
        $em->flush();
        
        $ref = $newmarque->getName() . "_" . $newmodele->getName() . "_" . $motorisation . "_N" . $car->getId();
        $car->setRef($ref);
        $em->persist($car);
        $em->flush();
        
        
        $file = [];
        
        $file['name'] = $result['photo1'];
        $file['tmp_name'] = '../web/img/cars/olds/'. $result['photo1'];
        
        if (strlen($result['photo1']) > 0){
            if(file_exists($file['tmp_name'])){
                $this->saveImage($file, $car, 0);
            }
        }
        
    }
    
     die;
    return "true";
    }
    
    
         
    public function saveImage($file, $car, $key){
            $image = new Image();
                
            $fileName = new \SplFileInfo($file['name']);
            $extension = $fileName->getExtension();
            $imageName = $car->getId() . "_" . date("YmdHis") . "_" . $key . ".". $extension;
//            $imageNameSmall = $car->getId() . "_" . date("YmdHis"). "_" . $key . "_small.". $extension;

            $carFolder = $car->getModele()->getMarque()->getName() . '_' . $car->getModele()->getName() . '_N' . $car->getId();
            $path = '../web/img/cars/'.$carFolder;
//            $pathBig = $path.'/big';
//            $pathSmall = $path.'/thumbs';

            if (!file_exists($path)){
              mkdir($path, 0777);
            }

//            if (!file_exists($pathBig)){
//              mkdir($pathBig, 0777);
//            }
//
//            if (!file_exists($pathSmall)){
//              mkdir($pathSmall, 0777);
//            }

            $completeNameOriginal = $path .'/'. $imageName;
//            $completeNameBig = $pathBig .'/'. $imageName;
//            $completeNameSmall = $pathSmall .'/'. $imageNameSmall;
//          
            $copie = copy($file['tmp_name'], $completeNameOriginal);
//
//            echo "<pre>";
//            var_dump($file);
//            var_dump($completeNameOriginal);
//            echo "</pre>";
//            
//            
//            die;
          
            if ($copie === true ){
            
//            $this->create_square_image($completeNameOriginal, $completeNameBig,750);
//            $this->create_square_image($completeNameOriginal, $completeNameSmall,200);

//            unlink($completeNameOriginal);

//            $image->setPath($carFolder.'/big/');
//            $image->setPathsmall($carFolder.'/thumbs/');
//            $image->setName($imageName);
//            $image->setNamesmall($imageNameSmall);
            $image->setPath($carFolder);
            $image->setPathsmall($carFolder);
            $image->setName($imageName);
            $image->setNamesmall($imageName);

            $image->setCar($car);
             $image->setMain(1);
            $image->setCar($car);    

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();
            }
            
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
			$new_height = $new_width*($original_height/$original_width);
                         
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
