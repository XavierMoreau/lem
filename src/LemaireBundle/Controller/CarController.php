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
use Symfony\Component\HttpFoundation\Response;
use DOMDocument;
use ZipArchive;

/**
 * Car controller.
 *
 * @Route("/car")
 */
class CarController extends Controller
{
    /**
     * Lists all car entities.
     *
     * @Route("/admin", name="car_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cars = $em->getRepository('LemaireBundle:Car')->findAll(array('date' => 'ASC' ));
        
        $carsActive = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => '1' ));
        $carsSold = $em->getRepository('LemaireBundle:Car')->findBy(array('vendu' => '1' ));
        
        $carsCentrale = $em->getRepository('LemaireBundle:Car')->findBy(array('statusCentrale' => '1' ));
        $carsCentralePb = $em->getRepository('LemaireBundle:Car')->findBy(array('statusCentrale' => '0'));

        $dateToCompare = date('y-m-d g:i:s', strtotime('-22 days'));
        $modeles = [];
        $nettoyage = [];
         foreach ($cars as $key => $car){
            $marque = $car->getModele()->getMarque()->getName();
            $modele = $car->getModele()->getName();
            if (!isset($modeles[$marque])){
              $modeles[$marque] = [];
            }
            
            if (!(in_array($modele,$modeles[$marque]))){
                $modeles[$marque][$key] = $car->getModele()->getName();
            }
            sort($modeles[$marque]);
            
            $dateCarSold = $car->getDateSold();
            $sold = $car->getVendu();
            
            if ($sold === true){
                if(strtotime($dateCarSold->format('y-m-d g:i:s')) < strtotime($dateToCompare)){
                    array_push($nettoyage, $car->getId());
                }            
            }
        }
     
        return $this->render('car/index.html.twig', array(
            'cars' => $cars,
            'modeles' => $modeles,
            'nbcars' => count($cars),
            'nettoyage' => count($nettoyage),
            'vendues' => count($carsSold),
            'visibles' => count($carsActive),
            'centrale' => count($carsCentrale),
            'centralepb' => count($carsCentralePb),
        ));
    }

 
    /**
     * Lists all car entities exported in La Centrae.
     * @Route("/admin/listcentrale", name="car_listcentrale")
     * @Method("GET")
     */
    public function listCentraleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cars = $em->getRepository('LemaireBundle:Car')->findAll(array('date' => 'ASC' ));
        
        $modeles = [];
        foreach ($cars as $key => $car){
            $marque = $car->getModele()->getMarque()->getName();
            $modele = $car->getModele()->getName();
            if (!isset($modeles[$marque])){
              $modeles[$marque] = [];
            }
            
            if (!(in_array($modele,$modeles[$marque]))){
                $modeles[$marque][$key] = $car->getModele()->getName();
            }
            sort($modeles[$marque]);         
        }    
        return $this->render('car/centrale.html.twig', array(
            'cars' => $cars,
            'modeles' => $modeles,
        ));       
    }
    
    /**
     * Creates a new car entity.
     *
     * @Route("/admin/new", name="car_new")
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

            if ($form['type'] !== ""){
                $getType = $em->getRepository('LemaireBundle:Type')->findById($form['type']);
                $type = $getType[0];
            }else{
                $type = null;
            }
            
            if ($form['cvfiscaux'] !== ""){
             $car->setCvfiscaux($form['cvfiscaux']);
            }else{
             $car->setCvfiscaux(null);
            }
            
            if ($form['annee'] !== ""){
             $car->setAnnee($form['annee']);
            }else{
             $car->setAnnee(null);
            }
            
             if ($form['kms'] !== ""){
             $car->setKms($form['kms']);
            }else{
             $car->setKms(null);
            }
            
             if ($form['portes'] !== ""){
             $car->setPortes($form['portes']);
            }else{
             $car->setPortes(null);
            }
            
            if ($form['places'] !== ""){
             $car->setPlaces($form['places']);
            }else{
             $car->setPlaces(null);
            }
            
             if ($form['prixdestock'] !== ""){
             $car->setPrixdestock($form['prixdestock']);
            }else{
             $car->setPrixdestock(null);
            }
            
             if ($form['prixgarantie'] !== ""){
             $car->setPrixgarantie($form['prixgarantie']);
            }else{
             $car->setPrixgarantie(null);
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
            
            if (isset($form['centrale'])){
                $car->setCentrale($form['centrale']);
            }else{
                $car->setCentrale(0);
            }
                        
            $dateSold = new \Datetime();
            
            if (isset($form['vendu']) ){
                $car->setVendu($form['vendu']);
                $car->setDateSold($dateSold);
                $car->setDateCentrale($dateSold);
                $car->setStatusCentrale(1);
                $car->setCommentCentrale("Vendu");
            }else{
                $car->setVendu(0);
                $car->setDateSold(null);
                $car->setDateCentrale(null);
                $car->setStatusCentrale(0);
                $car->setCommentCentrale(null);
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
            $this->sendToLaCentrale();
            return $this->redirectToRoute('car_show', array('id' => $car->getId()));
        }
        
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $modeles = $em->getRepository('LemaireBundle:Modele')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        $options = $em->getRepository('LemaireBundle:Options')->findAll();
        $types = $em->getRepository('LemaireBundle:Type')->findAll();
        
        $marquessort = [];
        $modelessort = [];
        $energiessort = [];
        $typessort = [];
        
        foreach($marques as $data){
            $marquessort[$data->getName()] = $data;
        }
        ksort($marquessort);
        
        foreach($modeles as $data){
            $modelessort[$data->getName()] = $data;
        }
        ksort($modelessort);
        
        foreach($energies as $data){
            $energiessort[$data->getName()] = $data;
        }
        ksort($energiessort);
        
        foreach($types as $data){
            $typessort[$data->getName()] = $data;
        }
        ksort($typessort);
  
        return $this->render('car/new.html.twig', array(
            'marques' => $marquessort,
            'modeles' => $modelessort,
            'energies' => $energiessort,
            'options' => $options,
            'types' => $typessort,
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
        if ($this->container->has('profiler'))
{
    $this->container->get('profiler')->disable();
}
        $optionArray = explode(", ",$car->getOptions());
        $countOptions = count($optionArray);
        $car->setOptions($optionArray);
        
       
        
        $em = $this->getDoctrine()->getManager();
        
        $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);
        
        $modele = $car->getModele();
        $energie = $car->getEnergie();
        $price = $car->getPrixdestock();
        $idCar = $car->getId();
        
        $carsautres = $this->getOtherCars($modele, $price, $energie, $idCar);
        

        
        
        $photosautres=[];
        foreach ($carsautres as $carautre){
            $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $carautre->getId()));
             
            if (isset($photo[0])){
                array_push($photosautres, $photo[0]);
            }
        }

        return $this->render('car/show.html.twig', array(
            'car' => $car,
            'photos' => $photos,
            'countoptions' => $countOptions,
            'cars' => $carsautres,
            'photosautres' => $photosautres
        ));
    }
    
    /**
     * Finds and displays a car entity.
     *
     * @Route("/{id}/print", name="car_print")
     * @Method("GET")
     */
    public function printAction(Car $car)
    {
        
        $optionArray = explode(", ",$car->getOptions());
        $countOptions = count($optionArray);
        $car->setOptions($optionArray);

        $em = $this->getDoctrine()->getManager();
        
        $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);

        return $this->render('car/print.html.twig', array(
            'car' => $car,
            'photos' => $photos,
            'countoptions' => $countOptions,
        ));
    }
    
    
    // Algo des voitures interressantes selon véhicule consulté 
    
    public function getOtherCars($modele, $price, $energie, $idCar) {
        
        $em = $this->getDoctrine()->getManager();
        
        $getCars = $em->getRepository('LemaireBundle:Car')->findBy(array("modele" => $modele, "energie" => $energie, "active" => true, "vendu" => false));
        
        $carsautres = [];
        
        shuffle($getCars);
        
        // 3 maxi même modèle, même énergie :
        $result = count($getCars);
          
        if ($result > 1){
            $j = 0;
            foreach($getCars as $car){
                if ($j < 2){
                    if($idCar !== $car->getId()){
                    array_push($carsautres, $car);
                    $j++;
                    }
                }
            }

        }else{
            foreach($getCars as $car){
                if ($idCar !== $car->getId()){   
                    array_push($carsautres, $car);
                }   
            }
        }

        $reste = 4 - count($carsautres);
        
        
        // 3 maxi prix +/- 5% :
        $percentage = 0.05;
        $cars_results = [];
        if ($price === 0 || $price === null){
            $price = 1000;
        }
   
        while(count($cars_results) <= $reste){
            
            $price5plus = $price * (1 + $percentage);
            $price5moins = $price * (1 - $percentage);
        
            $CARS_QUERY = 'SELECT * FROM car WHERE prixdestock < '.$price5plus.' AND prixdestock > '.$price5moins.' AND active = 1 AND vendu = 0;';    
            $cars_statement = $em->getConnection()->prepare($CARS_QUERY);
            $cars_statement->execute();
            $cars_results = $cars_statement->fetchAll();
            $percentage = $percentage + 0.05; 
        }
                
        shuffle($cars_results);
        $i = 0;        
        foreach($cars_results as $carPrice){
           
            if ($i < $reste){
                
                $getCarPrice = $em->getRepository('LemaireBundle:Car')->findById($carPrice['id']);
                
                if ($idCar !== $getCarPrice[0]->getId() && !(in_array($getCarPrice[0], $carsautres))){   
                    array_push($carsautres, $getCarPrice[0]);
                    $i++;
                }                  
            }        
        }
        return $carsautres;
    }

    /**
     * Displays a form to edit an existing car entity.
     *
     * @Route("/admin/{id}/edit", name="car_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Car $car)
    {

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
        $optionsOrigin = $car->getOptions();
        
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
            $car->setOptions($optionsOrigin);
            
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
           //     if ($car->getEnergie()->getId() !== $form['energie']){
                    $getEnergie = $em->getRepository('LemaireBundle:Energie')->findById($form['energie']);
                    $energie = $getEnergie[0];
            //    }    
            }else{
                $energie = null;
            }

            if ($form['type'] !== ""){
                    $getType = $em->getRepository('LemaireBundle:Type')->findById($form['type']);
                    $type = $getType[0];
            }else{
                $type = null;
            }
            
            if ($form['cvfiscaux'] !== ""){
             $car->setCvfiscaux($form['cvfiscaux']);
            }else{
             $car->setCvfiscaux(null);
            }
            
            if ($form['annee'] !== ""){
             $car->setAnnee($form['annee']);
            }else{
             $car->setAnnee(null);
            }
            
             if ($form['kms'] !== ""){
             $car->setKms($form['kms']);
            }else{
             $car->setKms(null);
            }
            
             if ($form['portes'] !== ""){
             $car->setPortes($form['portes']);
            }else{
             $car->setPortes(null);
            }
            
            if ($form['places'] !== ""){
             $car->setPlaces($form['places']);
            }else{
             $car->setPlaces(null);
            }
            
             if ($form['prixdestock'] !== ""){
             $car->setPrixdestock($form['prixdestock']);
            }else{
             $car->setPrixdestock(null);
            }
            
             if ($form['prixgarantie'] !== ""){
             $car->setPrixgarantie($form['prixgarantie']);
            }else{
             $car->setPrixgarantie(null);
            }
           
            $car->setModele($modele);
            $car->setEnergie($energie);
            $car->setType($type);
            $car->setSerie($form['serie']);
            $car->setMotorisation($form['motorisation']);
            $car->setCouleur($form['couleur']);
            $car->setBoitevitesse($form['boitevitesse']);
            
                        
            if (isset($form['active'])){
                $car->setActive($form['active']);
            }else{
                $car->setActive(false);
            }
            
            if (isset($form['centrale'])){
                $car->setCentrale($form['centrale']);
            }else{
                $car->setCentrale(false);
            }           
            
            $dateSold = new \Datetime();
            
            if (isset($form['vendu']) ){         
                if ($car->getVendu() === false ){
                    $car->setVendu($form['vendu']);
                    $car->setDateSold($dateSold);
                    $car->setDateCentrale($dateSold);
                    $car->setStatusCentrale(true);
                    $car->setCommentCentrale("Vendu");
                }
            }else{
                $car->setVendu(0);
                $car->setDateSold(null);
                $car->setDateCentrale(null);
                $car->setStatusCentrale(false);
                $car->setCommentCentrale(null);
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
  
            $this->sendToLaCentrale();
            
            return $this->redirectToRoute('car_show', array('id' => $car->getId()));
        }

        $marquessort = [];
        $modelessort = [];
        $energiessort = [];
        $typessort = [];
        
        foreach($marques as $data){
            $marquessort[$data->getName()] = $data;
        }
        ksort($marquessort);
        
        foreach($modeles as $data){
            $modelessort[$data->getName()] = $data;
        }
        ksort($modelessort);
        
        foreach($energies as $data){
            $energiessort[$data->getName()] = $data;
        }
        ksort($energiessort);
        
        foreach($types as $data){
            $typessort[$data->getName()] = $data;
        }
        ksort($typessort);
 
        return $this->render('car/edit.html.twig', array(
            'marques' => $marquessort,
            'modeles' => $modelessort,
            'energies' => $energiessort,
            'options' => $optionsBase,
            'types' => $typessort,
            'car' => $car,
            'photos'=> $photos
        ));
    }
        
   
        
    public function saveImage($file, $car, $key){
            $image = new Image();
                
            $fileName = new \SplFileInfo($file['name']);
            $extension = $fileName->getExtension();
            $imageName = $car->getId() . "_" . date("YmdHis") . "_" . $key . ".". $extension;
            $imageNameSmall = $car->getId() . "_" . date("YmdHis"). "_" . $key . "_small.". $extension;

            $carFolder = $car->getModele()->getMarque()->getName() . '_' . $car->getModele()->getName() . '_N' . $car->getId();
            $carFolder = str_replace(" ", "", $carFolder);
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
            
                    
           $copie = false;

           $move = move_uploaded_file($file['tmp_name'], $completeNameOriginal);
           if ($move === false){ 
            $copie = copy($file['tmp_name'], $completeNameOriginal);
           }
            if ($copie === true || $move === true){


                $this->create_square_image($completeNameOriginal, $completeNameBig,750);
                $this->create_square_image($completeNameOriginal, $completeNameSmall,200);

                unlink($completeNameOriginal);

                $image->setPath($carFolder.'/big/');
                $image->setPathsmall($carFolder.'/thumbs/');
                $image->setName($imageName);
                $image->setNamesmall($imageNameSmall);

                $image->setCar($car);
            }
            
            return $image;
            
    }


    /**
     * Deletes a car entity.
     *
     * @Route("/admin/delete/{id}", name="delete_car")
     * @Method("GET")
     */
    public function deleteCarAction(Car $car)
    {
           
         $em = $this->getDoctrine()->getManager();
         
         $carFolder = $car->getModele()->getMarque()->getName() . '_' . $car->getModele()->getName() . '_N' . $car->getId();
         $path = '../web/img/cars/'.$carFolder;
         
         $this->rrmdir($path);
         
         $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);
         
         foreach ($photos as $photo){
            $em->remove($photo);
            $em->flush();       
            
         }

           $em->remove($car);
           $em->flush();
           
           
        

        return $this->redirectToRoute('car_index');
    }
    
    function rrmdir($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir); 
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (is_dir($dir."/".$object))
                $this->rrmdir($dir."/".$object);
              else
                unlink($dir."/".$object); 
            } 
          }      
          rmdir($dir); 
        } 
 }
 
    /**
     * Deletes a car entity.
     *
     * @Route("/admin/automatic/auto", name="delete_car_auto")
     * 
     */
 
    public function deleteCarsOldAutomatic(){
    
        $em = $this->getDoctrine()->getManager();
        $cars = $em->getRepository('LemaireBundle:Car')->findAll();
        
        $date = new \DateTime;
        $dateToCompare = date('y-m-d g:i:s', strtotime('-22 days'));
        
        
        foreach ($cars as $car){
            $dateCarSold = $car->getDateSold();
            $sold = $car->getVendu();
            
            if ($sold === true && $dateCarSold === null){             
                $dateCarSold = $date;        
                $car->setDateSold($dateCarSold);
                $em->persist($car);
                $em->flush();
            }
            
            if ($sold === true){
  
              if(strtotime($dateCarSold->format('y-m-d g:i:s')) < strtotime($dateToCompare)){

                 $this->deleteCarAction($car);
                }
            
            }
        }
  
        return $this->redirectToRoute('car_index');
    
    }
 
 
    /**
     * Send Cars to La Centrale.
     *
     * @Route("/sendtolacentrale/auto", name="send_to_lacentrale")
     * @Method("GET")
     */
 
    public function sendToLaCentrale(){
    
        $em = $this->getDoctrine()->getManager();

        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('centrale' => true));
        $date = new \Datetime();
         
        $xml_string = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml_string .= '<client>';
        
        foreach($cars as $car){
                        
            if ($car->getVendu() === true ){

                if ($car->getStatusCentrale() === true || $car->getCentrale() === true ){
                    
                    $car->setDateCentrale($date);
                    $car->setStatusCentrale(false);
                    $car->setCentrale(false);
                    $car->setCommentCentrale("Vendu");
                    
                    $em->persist($car);
                    $em->flush();  
                }    
            continue;
            } 
            
            $checkCar = $this->checkCarForCentrale($car);
            $xml_string_car = null;
            if ($checkCar["response"] === true){
            
                $xml_string_car .= '<annonce id="'. $car->getId() .'">';
                    
                    // OBLIGATOIRE - Checké dans checkCarForCentrale()
                    $xml_string_car .= '<reference>N'. $car->getId() .'</reference>';
                    
                    $xml_string_car .= '<date_saisie>'. date_format($car->getDate(), "d/m/Y") .'</date_saisie>';
                    
                    // Requete pour récupérer les photos du véhicule :
                    $photos = $em->getRepository('LemaireBundle:Image')->findByCar($car);
                    
                    if (isset($photos)){
                        $xml_string_car .= '<photos>';
                        foreach ($photos as $photo){ 
                            $xml_string_car .= '<photo>http://www.lemaire-autos.com/web/img/cars/'.$photo->getPath().$photo->getName().'</photo>';
                        }
                        $xml_string_car .= '</photos>';
                    }else{
                        $car->setDateCentrale($date);
                        $car->setStatusCentrale(false);
                        $car->setCommentCentrale($checkCar["Pas de photo"]); 
                        $em->persist($car);
                        $em->flush(); 
                        continue;
                    }
                    $xml_string_car .= '<contact_a_afficher>Service Commercial</contact_a_afficher>';
                    $xml_string_car .= '<email_a_afficher>commercial@lemaire-autos.com</email_a_afficher>';
                    $xml_string_car .= '<telephone_mobile_a_afficher>0760246229</telephone_mobile_a_afficher>';

                    $xml_string_car .= '<vehicule>';
                    
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()
                        $xml_string_car .='<marque>'. $car->getModele()->getMarque()->getName() .'</marque>';
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()
                        $xml_string_car .='<modele>'. $car->getModele()->getName() .'</modele>';
                        
                        if ($this->isOk($car->getMotorisation())){
                        $xml_string_car .='<version>'. $car->getMotorisation() . ' ' .$car->getSerie() .'</version>';
                        }
                        if ($this->isOk($car->getBoitevitesse())){
                        $xml_string_car .='<boite_de_vitesse>'. $car->getBoitevitesse() .'</boite_de_vitesse>';
                        }
                        
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()
                        $xml_string_car .='<energie>'. $car->getEnergie()->getName() .'</energie>';
                        
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()
                        $xml_string_car .='<couleur>'. $car->getCouleur() .'</couleur>';
                        
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()                        
                        $xml_string_car .='<kilometrage>'.  $car->getKms().'</kilometrage>';
                        
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()                       
                        $xml_string_car .='<millesime>'. $car->getAnnee().'</millesime>';
                        
                        if ($this->isOk($car->getOptions())){
                        $xml_string_car .='<equipements>'. $car->getOptions() .'</equipements>';
                        }
                        if ($this->isOk($car->getCvfiscaux())){
                        $xml_string_car .='<puissance_fiscale>'. $car->getCvfiscaux() .'</puissance_fiscale>';
                        }
                        if ($this->isOk($car->getPortes())){
                        $xml_string_car .='<nb_portes>'. $car->getPortes() .'</nb_portes>';
                        }
                        if ($this->isOk($car->getPlaces())){
                        $xml_string_car .='<nb_places>'. $car->getPlaces() .'</nb_places>';
                        }
                        if ($car->getType()){
                            if ($this->isOk($car->getType()->getName())){
                            $xml_string_car .='<carrosserie>'. $car->getType()->getName() .'</carrosserie>';
                            }
                        }
                    $xml_string_car .= '</vehicule>';

                    $xml_string_car .='<offre>';
                        
                        // OBLIGATOIRE - Checké dans checkCarForCentrale()
                        if ($this->isOk($car->getPrixdestock())){
                        $xml_string_car .='<prix>'. $car->getPrixdestock() .'</prix>';
                        $xml_string_car .='<garantie_libelle>Prix destockage</garantie_libelle>';
                        }elseif ($this->isOk($car->getPrixgarantie())){
                        $xml_string_car .='<prix>'. $car->getPrixgarantie() .'</prix>';
                        $xml_string_car .='<garantie_libelle>Prix avec garantie</garantie_libelle>';
                        $xml_string_car .='<garantie>12</garantie>';
                        }
                       
                        $xml_string_car .='<controle_technique>OK</controle_technique>';

                    $xml_string_car .='</offre>';

                $xml_string_car .= '</annonce>';
                
                $xml_string .= $xml_string_car;
                
                $car->setDateCentrale($date);
                $car->setStatusCentrale(true);
                $car->setCommentCentrale($checkCar["problemes"]);
                
            }else{               
                $car->setDateCentrale($date);
                $car->setStatusCentrale(false);
                $car->setCommentCentrale($checkCar["problemes"]);
                                
            }
            $em->persist($car);
            $em->flush();  
        }
        
       
       
        $xml_string .= '</client>';
        
        $xml_string_final = str_replace("&", "et", $xml_string);

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
              
        
        $dom->loadXML($xml_string_final);

        $dateFile = new \Datetime();

        // $folder = '../export/';
        $folder = null;

        $logFileName = 'Export_Centrale_'.$dateFile->format('Y-m-d_H-i-s') .'.xml';
        $logFile = $folder . $logFileName;

        $dom->save($logFile);

        $logZip = new ZipArchive;
           if ($logZip->open($folder . 'Exports Centrale.zip', ZipArchive::CREATE) === TRUE)
           {
               // Add files to the zip file
               $logZip->addFile($logFile);

               // All files are added, so close the zip file.
               $logZip->close();
           }

        unlink($logFile);

        $dom->save($folder . 'ag541229.xml');

        $zip = new ZipArchive;
            if ($zip->open($folder . 'ag541229.zip', ZipArchive::CREATE) === TRUE)
            {
                // Add files to the zip file
                $zip->addFile($folder . 'ag541229.xml');

                // All files are added, so close the zip file.
                $zip->close();
            }

            
        $file = 'ag541229.zip';
        $remote_file = 'ag541229.zip';
    
        $ftp_server = "ftp.ubiflow.net";
        $ftp_user_name = "ag541229";
        $ftp_user_pass = "3q39c0dc";

        // Mise en place d'une connexion basique
        $conn_id = ftp_connect($ftp_server);
        // mode passif


        // Identification avec un nom d'utilisateur et un mot de passe
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        ftp_pasv($conn_id, true);
        // Charge un fichier
        if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
         echo "Le fichier $file a été chargé avec succès\n";
        } else {
         echo "Il y a eu un problème lors du chargement du fichier $file\n";
        }

        // Fermeture de la connexion
        ftp_close($conn_id);

    

        $response = new Response($xml_string_final);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    
    }
 
  
     /**
     * Check if car is ok for Centrale.
     *
     * @param Car $car The car entity
     *
     * @return true
     */
    private function checkCarForCentrale(Car $car)
    {
        
        $response = true;
        
        $problemes = '';

        
        if (!($this->isOk($car->getRef()))){
            $problemes .= "Réference nulle.\n";
            $response = false;
        }
        
        if ((!($car->getModele())) 
         || (!($car->getModele()->getMarque()))
         || (!($this->isOk($car->getModele()->getMarque()->getName())))){
                    $problemes .= "Marque non renseignée.\n";
                    $response = false;
        }
        
        if ((!($car->getModele()))
         || (!($this->isOk($car->getModele()->getName())))){
            $problemes .= "Modèle non renseigné.\n";
            $response = false;
        }
        
        if ((!($car->getEnergie()))
         || (!($this->isOk($car->getEnergie()->getName())))){
            $problemes .= "Energie non renseignée.\n";
            $response = false;
        }
        
        if (!($this->isOk($car->getBoitevitesse()))){
            $problemes .= "Boite de vitesse non renseignée.\n";
            $response = false;
        }

        if ((!($this->isOk($car->getPrixdestock())))
            && (!($this->isOk($car->getPrixgarantie())))){
            $problemes .= "Prix non renseigné.\n";
            $response = false;
                        }
          
        if (!($this->isOk($car->getCouleur()))){
            $problemes .= "Couleur non renseignée.\n";
            $response = false;
        }elseif($this->checkColor(strtolower($car->getCouleur())) !== true){
                $problemes .= "Couleur non correspondante à la liste.\n";
                $response = false;
        }
               
        if (!($this->isOk($car->getKms()))){
            $problemes .= "Kilométrage non renseigné.\n";
            $response = false;
        }
        
        if (!($this->isOk($car->getAnnee()))){
            $problemes .= "Année non renseignée.\n";
            $response = false;
        }
        
        if ($response === true){
            $problemes = 'OK';
        }
       
        
        

        
    return ["response" => $response, "problemes" => $problemes];
        
    }
    

     /**
     * Check if colors match in array for Centrale.
     *
     * @param $color
     *
     * @return true
     */
    private function checkColor($color)
    {
        $response = false;
        
        $colors = ["argent", "autre", "beige", "blanc", "bleu", "bleu azur", "bleu clair", "bleu foncé", "bleu marine", "bordeaux", "bronze", "brun", "cassis", "cerise", "cuivre", "framboise", "gris", "gris anthracite", "gris clair", "gris foncé", "ivoire", "jaune", "kaki", "marron", "marron clair", "moka", "noir", "or", "orange", "platine", "prune", "rose", "rouge", "rouge foncé", "sable", "titane", "turquoise", "vert", "vert amande", "vert foncé", "violet"];
        
        if (in_array($color, $colors)){
            $response = true;
        }
        
        return $response;
    }
    
    private function isOK($data)
    {
        
        if ($data === null || $data === '' || $data === ' ' || $data === "0"){
            return false;
        }else{
            return true;
        }
            
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
		
                               
                $rect =  ['x' => 0, 'y' => 0, 'width' => $square_size*1.33333333, 'height' => $square_size];
                
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
