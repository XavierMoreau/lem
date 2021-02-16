// Chargement de la page principale - Cascade des vignettes

$(window).on('load', function(){
	
//    var count = $(".vignette").length;
//    $(".vignette").each(function(key, i){
//		$(this).delay(1*key).queue(function(){
//			$(this).addClass("show");
//                        if (key === count-1){
//                            $('.loading').addClass('hidden');   
//                        }
//		});
//	});
        
        $('.car-counter').text($(".vignette").not('.hidden-brand').not('.hidden-energy').not('.hidden-price').length);
});

checkCookie();

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  var test = getCookie("lemoto");
  if (test !== "1") {
    showModalCovid();
  } 
}

function showModalCovid() {
   $('.modalcovid').removeClass('hidden');
   $('.covidclick').on('click', function(){
       $('.covidexp2').removeClass('hidden');
       $('.covidclick').addClass('hidden');
   });
   $('.btn-covid').on('click', function(){
       if ($('#checkboxcovid').is(':checked')){
           setCookie("lemoto",1,15);
       } else {
           setCookie("lemoto",1,1);
       }
       $('.modalcovid').addClass('hidden');
   });
}

// Menu

var menu = $('.menu');
menu.on('click', function(){
    $('.menu-small').removeClass('hidden');
    event.stopImmediatePropagation();
    $( "div.menu-link" )
        .mouseover(function() {
          $( this ).addClass('menu-hover');
        })
        .mouseout(function() {
          $( this ).removeClass('menu-hover');
        });
    $( "div.menu-link" ).on('click', function(){
        $('.menu-small').addClass('hidden');
    });
    
    $('body').on('click', function(){
        $('.menu-small').addClass('hidden');
    });
});
    
var countPrices = function(){
    
    var vignettesOn = $(".vignette").not('.hidden-brand').not('.hidden-energy').not('.hidden-price');
    
    var priceSelect = $(".select-price").find('select').find('option');
    priceSelect.each(function(){
        var priceMin = $(this).val();
        if (priceMin > -1){
            var priceMax = $(this).attr('data-max');
            var count = 0;
            vignettesOn.each(function(){
                var price = $(this).attr('data-price');
//                var price = priceHtml.replace('€','');

                
                if (priceMin <= price && price <= priceMax){
                    count = count + 1;
                }
            });
            
            var str = $(this).html();
            var newstr=str.replace(/\((.+?)\)/g, "("+count+")");
            
            $(this).html(newstr);
        }
    });
  
      
};

countPrices();


// Modification des vignettes en fonction des séléctions
var brandSelect = $(".select-brand");
brandSelect.on('change', function() {
	var brandValue = $(this).find('select').val();
	if (brandValue !== ""){	
		$(".vignette").each(function(){
			var brand = $(this).attr('data-brand');
			if (brandValue === brand){
                            $(this).removeClass('hidden-brand');

			}else{				
                            $(this).addClass('hidden-brand');
			}
		});
                $(".select-model").removeClass('hidden');
	}else{
            $(".vignette").removeClass('hidden-brand');
            $(".vignette").removeClass('hidden-model');
            $(".select-model").addClass('hidden');
		
	}
                //compteur de vignettes affichées.
        $('.car-counter').text($(".vignette").not('.hidden-brand').not('.hidden-energy').not('.hidden-price').length);
        countPrices();
});

var modelSelect = $(".select-model");
modelSelect.on('change', function() {
	var modelValue = $(this).find('select').val();
	if (modelValue !== ""){	
		$(".vignette").each(function(){
			var model = $(this).attr('data-model');
			if (modelValue === model){
                            $(this).removeClass('hidden-model');

			}else{				
                            $(this).addClass('hidden-model');
			}
		});
	}else{
            $(".vignette").removeClass('hidden-model');
            $(".select-model").addClass('hidden');		
	}
});



var priceSelect = $(".select-price");
priceSelect.on('change', function() {
	var priceValue = $(this).find('select').val();
        var priceMax = $(this).find('select').find('option:selected').attr('data-max');
	if (priceValue !== ""){	
		$(".vignette").each(function(){
			var price = $(this).attr('data-price');
                       
                        
			if (parseInt(priceValue) <= parseInt(price) && parseInt(price) <= parseInt(priceMax)){
                            
				$(this).removeClass('hidden-price');
                    
			}else{
				$(this).addClass('hidden-price');
			}
		});
	}else{
		$(".vignette").removeClass('hidden-price');
	}
                //compteur de vignettes affichées.
        $('.car-counter').text($(".vignette").not('.hidden-brand').not('.hidden-energy').not('.hidden-price').length);
});


var kmsSelect = $(".select-kms");
kmsSelect.on('change', function() {
	var kmsValue = $(this).find('select').val();
        var kmsMax = $(this).find('select').find('option:selected').attr('data-max');
	if (kmsValue !== ""){	
		$(".vignette").each(function(){
			var kms = $(this).attr('data-kms');
                       
                        
			if (parseInt(kmsValue) <= parseInt(kms) && parseInt(kms) <= parseInt(kmsMax)){
                            
				$(this).removeClass('hidden-kms');
                    
			}else{
				$(this).addClass('hidden-kms');
			}
		});
	}else{
		$(".vignette").removeClass('hidden-kms');
	}
});



var energySelect = $(".select-energy");
energySelect.on('change', function() {
	var energyValue = $(this).find('select').val();
	if (energyValue !== ""){	
		$(".vignette").each(function(){
			var energy = $(this).attr('data-energy');
			if (energyValue === energy){
				$(this).removeClass('hidden-energy');
			}else{
				$(this).addClass('hidden-energy');
			}
		});
	}else{
		$(".vignette").removeClass('hidden-energy');
	}
        //compteur de vignettes affichées.
        $('.car-counter').text($(".vignette").not('.hidden-brand').not('.hidden-energy').not('.hidden-price').length);
        countPrices();
});


// Pages New et Edit
var marque = $('#lemairebundle_car_marque');
var addMarqueZone = $('#add-marque');
var modele = $('#lemairebundle_car_modele');
var addModeleZone = $('#add-modele');
var energie = $('#lemairebundle_car_energie');
var addEnergieZone = $('#add-energie');
var type = $('#lemairebundle_car_type');
var addTypeZone = $('#add-type');
var serie = $('#lemairebundle_car_serie');
var motorisation = $('#lemairebundle_car_motorisation');
var cvfiscaux = $('#lemairebundle_car_cvfiscaux');
var annee = $('#lemairebundle_car_annee');
var kms = $('#lemairebundle_car_kms');
var options = $('#lemairebundle_car_options');
var couleur = $('#lemairebundle_car_couleur');
var boitevitesse = $('#lemairebundle_car_boitevitesse');
var portes = $('#lemairebundle_car_portes');
var places = $('#lemairebundle_car_places');
var prixdestock = $('#lemairebundle_car_prixdestock');
var prixgarantie = $('#lemairebundle_car_prixgarantie');
var vendu = $('#lemairebundle_car_vendu');
var promotion = $('#lemairebundle_car_promotion');
var active = $('#lemairebundle_car_active');
var photoexist = $('.newcar-photos-small-zone');
var photonew = $('.gallery');

var activeCheck = $('#lemairebundle_car_active');
var alerteVisible = $('#alerte-visible');

var centraleCheck = $('#lemairebundle_car_centrale');
var alerteCentrale = $('#alerte-centrale');

var saveButton = $('.btn-save');
var alerteSave = $('#alerte-save');


$(window).on('load', function(){
    checkCarBeforeSave();
    checkCarBeforePublish();
    checkCarBeforeCentrale();
});

$('input, select, div.gallery').on('change', function(){ 
    checkCarBeforeSave();
    checkCarBeforePublish();
    checkCarBeforeCentrale();
});

var checkCarBeforeSave = function(){
    var messageSave = '';
    var marque = $('#lemairebundle_car_marque');
    var modele = $('#lemairebundle_car_modele');
    var addMarqueZoneVal = $('#add-marque :input').val();
    var addModeleZoneVal = $('#add-modele :input').val();
    saveButton.attr('disabled', true);
    if (marque.val() === "" && addMarqueZoneVal === ""){
        messageSave = messageSave + '<li>Marque</li>';    
    }
    if (modele.val() === "" && addModeleZoneVal === ""){
        messageSave = messageSave + '<li>Modèle</li>';
    }
    
    if (messageSave === ""){
            saveButton.attr('disabled', false);
            alerteSave.html("");
    }else{
        alerteSave.html("<ul>Obligatoire pour Enregistrer :" + messageSave + "</ul>");
    }
};

var checkCarBeforePublish = function(){
    var messageVisible = '';
    activeCheck.attr('disabled', true);
    if (marque.val() === "" && addMarqueZone.val() === ""){
        messageVisible = messageVisible + '<li>Marque</li>';    
    }
    if (modele.val() === "" && addModeleZone.val() === ""){
        messageVisible = messageVisible + '<li>Modèle</li>';    
    }
    if (energie.val() === "" && addEnergieZone.val() === ""){
        messageVisible = messageVisible + '<li>Energie</li>';    
    }
    if (photoexist.html() === "" && photonew.html() === ""){
        messageVisible = messageVisible + '<li>Photo</li>'; 
    }
    if ($("input.checkmain:checked").length === 0){
        messageVisible = messageVisible + '<li>Photo principale</li>';
    }
    if (messageVisible === ""){
        activeCheck.attr('disabled', false);
        alerteVisible.html("");
    }else{
        alerteVisible.html("<ul>Obligatoire pour Publier :" + messageVisible + "</ul>");
        activeCheck.attr('checked', false);
    }
    
};



var checkCarBeforeCentrale = function(){
    var messageVisible = '';
    centraleCheck.attr('disabled', true);
    if (marque.val() === "" && addMarqueZone.val() === ""){
        messageVisible = messageVisible + '<li>Marque</li>';    
    }
    if (modele.val() === "" && addModeleZone.val() === ""){
        messageVisible = messageVisible + '<li>Modèle</li>';    
    }
    if (energie.val() === "" && addEnergieZone.val() === ""){
        messageVisible = messageVisible + '<li>Energie</li>';    
    }
    
    if (couleur.val() === "" || checkColor(couleur.val() === false)){
    messageVisible = messageVisible + '<li>Couleur (absente ou hors liste)</li>';    
    }
    
    if (boitevitesse.val() === ""){
        messageVisible = messageVisible + '<li>Boite de vitesse</li>';    
    }
    
    if (kms.val() === ""){
    messageVisible = messageVisible + '<li>Kilométrage</li>';    
    }
    
    if (annee.val() === ""){
    messageVisible = messageVisible + '<li>Année</li>';    
    }
    
    if (photoexist.html() === "" && photonew.html() === ""){
        messageVisible = messageVisible + '<li>Photo</li>'; 
    }

    if (messageVisible === ""){
        centraleCheck.attr('disabled', false);
        alerteCentrale.html("");
    }else{
        alerteCentrale.html("<ul>Obligatoire pour La Centrale :" + messageVisible + "</ul>");
        centraleCheck.attr('checked', false);
    }
    
};
   
var checkColor = function(color){
    
    var colors = ["argent", "autre", "beige", "blanc", "bleu", "bleu azur", "bleu clair", "bleu foncé", "bleu marine", "bordeaux", "bronze", "brun", "cassis", "cerise", "cuivre", "framboise", "gris", "gris anthracite", "gris clair", "gris foncé", "ivoire", "jaune", "kaki", "marron", "marron clair", "moka", "noir", "or", "orange", "platine", "prune", "rose", "rouge", "rouge foncé", "sable", "titane", "turquoise", "vert", "vert amande", "vert foncé", "violet"];

    return colors.includes(color);
    
};


// Selection du modèle en fonction de la marque


    var marqueId = $('#lemairebundle_car_marque').val();
    modele.find('option[data-marque="' + marqueId + '"]').removeClass('hidden');
    modele.find('option[data-marque!="' + marqueId + '"]').addClass('hidden');

    

marque.on('change', function() {

    var marqueId = $('#lemairebundle_car_marque').val();
    modele.find('option[data-marque="' + marqueId + '"]').removeClass('hidden');
    modele.find('option[data-marque!="' + marqueId + '"]').addClass('hidden');
    modele.val('');
    
});

// SELECTS avec création de nouveau
var addMarqueButton = $('.add-marque');
var removeMarqueButton = $('.remove-marque');
addMarqueButton.on('click', function() {
    removeMarqueButton.removeClass('hidden');
    addMarqueButton.addClass('hidden');
    marque.addClass('hidden');
    marque.val("");
    addMarqueZone.removeClass('hidden');
});
removeMarqueButton.on('click', function() {
    removeMarqueButton.addClass('hidden');
    addMarqueButton.removeClass('hidden');
    marque.removeClass('hidden');
    addMarqueZone.addClass('hidden');
    addMarqueZone.val("");
});


var addModeleButton = $('.add-modele');
var removeModeleButton = $('.remove-modele');
addModeleButton.on('click', function() {
    removeModeleButton.removeClass('hidden');
    addModeleButton.addClass('hidden');
    modele.addClass('hidden');
    modele.val("");
    addModeleZone.removeClass('hidden');
});
removeModeleButton.on('click', function() {
    removeModeleButton.addClass('hidden');
    addModeleButton.removeClass('hidden');
    modele.removeClass('hidden');
    addModeleZone.addClass('hidden');
    addModeleZone.val("");
});


var addEnergieButton = $('.add-energie');
var removeEnergieButton = $('.remove-energie');
addEnergieButton.on('click', function() {
    removeEnergieButton.removeClass('hidden');
    addEnergieButton.addClass('hidden');
    energie.addClass('hidden');
    energie.val("");
    addEnergieZone.removeClass('hidden');
});
removeEnergieButton.on('click', function() {
    removeEnergieButton.addClass('hidden');
    addEnergieButton.removeClass('hidden');
    energie.removeClass('hidden');
    addEnergieZone.addClass('hidden');
    addEnergieZone.val("");
});


var addTypeButton = $('.add-type');
var removeTypeButton = $('.remove-type');
addTypeButton.on('click', function() {
    removeTypeButton.removeClass('hidden');
    addTypeButton.addClass('hidden');
    type.addClass('hidden');
    type.val("");
    addTypeZone.removeClass('hidden');
});
removeTypeButton.on('click', function() {
    removeTypeButton.addClass('hidden');
    addTypeButton.removeClass('hidden');
    type.removeClass('hidden');
    addTypeZone.addClass('hidden');
    addTypeZone.val("");
});

// Ajout et suppression d'options
var addOptionsButton = $('.add-option');
addOptionsButton.on('click', function() {
    
    if(window.location.href.indexOf("edit") > -1) {
        var cross = '../../../../web/img/cross.png';
    }else{
        var cross = '../../../web/img/cross.png';
    }
    
    
    var optionsCount = $('.option').find('input').length;
    var newCount = optionsCount + 1;
    $(this).before('<div id="option'+ newCount +'" class="new-car-input-option"><label for="lemairebundle_car_options_'+ newCount +'"></label><input type="text" name="lemairebundle_car[option supp]['+ newCount +']"><div class="pointer deloption" id="'+ newCount +'"><img src="'+cross+'" alt="Supprimer" width="15"></div></div>'); 

    deleteOptions();
});

var deleteOptions = function(){
    var delOption = $('.deloption');
    delOption.on('click', function(){

        var idOption = $(this).attr("id");
        var blockOption = $('#option'+idOption);
        blockOption.addClass('hidden');

        blockOption.find('input').attr("value","");    
    });
};


var btnDeleteCar = $(".btn-del");
var freeze = $(".freeze-modal");
var modal = $(".modal");
var btnNo = $(".btn-no");
btnDeleteCar.on('click',function(){
    modal.removeClass('hidden');
    freeze.removeClass('hidden');
});
btnNo.on('click',function(){
    modal.addClass('hidden');
    freeze.addClass('hidden');
});

// copie vers LBC
var btnCopyLBC = $(".btn-lbc");

btnCopyLBC.on('click',function(){
    
   freeze.removeClass('hidden');
   $(".modal-lbc").removeClass('hidden');
   $(".message-lbc").html("Copie en cours...");
   $(".btn-yes-lbc").html("Patientez...");
   $(".btn-yes-lbc").prop("disabled", true);
   
    
    var copyMarque = $("#lemairebundle_car_marque option:selected").text();
    var copyModele = $("#lemairebundle_car_modele option:selected").text();
    var copyMotorisation = $("#lemairebundle_car_motorisation").val();
    var copyAnnee = $("#lemairebundle_car_annee option:selected").text();
    var copyCv = $("#lemairebundle_car_cvfiscaux option:selected").text();
    
    var partie1 = "La société LEMAIRE Automobiles, implantée depuis plus de 30 ans à Dommartin-les-Toul, vous propose une sélection de voitures toutes marques à prix cassés et adaptée à tous les budgets !\nUn choix de plus de 100 véhicules en stock vous attend sur notre parc d'exposition. N'hésitez pas à nous rendre visite !\n\n";
    var partie2 = "Affaire à saisir : \n\n" + copyMarque + " " + copyModele+ " " + copyMotorisation + ", de "+ copyAnnee+", puissance fiscale "+ copyCv+" cv.\n";
    
    var options = [];
    
    var checkedOptions = $(".new_car_options_list input:checkbox:checked");
    checkedOptions.each(function(){
        var optionCheck = $(this).next().text();
        options.push(optionCheck);    
    });
    
    var supplOptions = $('.new-car-input-option input');
    supplOptions.each(function(){
        var optionSuppl = $(this).val();
        options.push(optionSuppl);    
    });
    
   var numberOptions = options.length;
   var optionsList = "";
   
   options.forEach(function(option,index){
       
        if (index === numberOptions - 1 ){
            optionsList = optionsList +", " +option +"...";
        }else if(index === 0){
            optionsList = option;
        }else{
            optionsList = optionsList +", " +option;
        }
   });
    
    var partie3 = "\nOptions * : " + optionsList+ "\n\n";

    var prixDestock = $("#lemairebundle_car_prixdestock").val();
    var prixGarantie = $("#lemairebundle_car_prixgarantie").val();


    var partie4 =  "Prix destockage : "+ prixDestock+" euros avec CT OK récent !\n\nPrix garantie 1 an : "+ prixGarantie+" euros avec CT OK + révision complète (vidange, filtres, freins, pneus...) + garantie nationale 12 mois dans tout le reseau " + copyMarque + " en France\n\n";

    var copyType = $("#lemairebundle_car_type option:selected").val();

    var gammesList = "";
    
    $.ajax({
        type: 'POST',
        url: "../../../admin/typeslbc/"+copyType+"/"+copyModele,
        success: function (gammes) {    
            var gammesOptions = gammes.length;
            if (gammesOptions > 0){
            gammes.forEach(function(gamme,index){
                
                if (index === gammesOptions - 1 ){
                    gammesList = gammesList +", " +gamme +"...";
                }else if(index === 0){
                    gammesList = gamme;
                }else{
                    gammesList = gammesList +", " +gamme;
                }
                
                var partie5 = "Même gamme : " + gammesList +"\n\n";
                var partie6 = "Véhicule visible sur notre parc occasion, situé au  :\n3 rue des Lurons\nPôle commercial Jeanne d’Arc\n54200 Dommartin les Toul\n(Au dessus du magasin BUT)\nOuvert du mardi au samedi de 9h30-12h00 et 14h00-18h30.\n\nTéléphone : 07 60 24 62 29\n\nNous recevons énormément de mails, il est donc préférable de prendre contact par téléphone ou de passer nous rendre visite. A bientôt !";

                var copyLbc = partie1 + partie2 + partie3 + partie4 + partie5 + partie6;
                 
                $(".message-lbc").html("Copie terminée !");
                $(".btn-yes-lbc").html("OK");
                $(".btn-yes-lbc").prop("disabled", false);
                
                $(".btn-yes-lbc").on("click", function(){
                        freeze.addClass('hidden');
                        $(".modal-lbc").addClass('hidden');

                        var $temp = $("<textarea>");
                        var brRegex = /<br\s*[\/]?>/gi;
                        $("body").append($temp);
                        $temp.val(copyLbc.replace(brRegex, "\r\n")).select();
                        document.execCommand("copy");
                        $temp.remove();
 
                   });
  
            });
        }
        else {
                       $(".message-lbc").html("La copie s'est faite quand même mais il n'y a pas de gamme équivalente");
                       $(".btn-yes-lbc").html("OK");
                
                        var partie5 = "Même gamme : \n\n";
                        var partie6 = "Véhicule visible sur notre parc occasion, situé au  :\n3 rue des Lurons\nPôle commercial Jeanne d’Arc\n54200 Dommartin les Toul\n(Au dessus du magasin BUT)\nOuvert du mardi au samedi de 9h30-12h00 et 14h00-18h30.\n\nTéléphone : 07 60 24 62 29\n\nNous recevons énormément de mails, il est donc préférable de prendre contact par téléphone ou de passer nous rendre visite. A bientôt !\n\n* Sous réserve d'erreur dans la liste d'options.";

                        var copyLbc = partie1 + partie2 + partie3 + partie4 + partie5 + partie6;
                
            
                        $(".btn-yes-lbc").on("click", function(){
                        freeze.addClass('hidden');
                        $(".modal-lbc").addClass('hidden');

                        var $temp = $("<textarea>");
                        var brRegex = /<br\s*[\/]?>/gi;
                        $("body").append($temp);
                        $temp.val(copyLbc.replace(brRegex, "\r\n")).select();
                        document.execCommand("copy");
                        $temp.remove();
 
                   });
        }    
        },
        error: function (resultat, erreur) {
                       console.log(erreur);
                       console.log(resultat);
                       $(".message-lbc").html("La copie s'est faite quand même mais il y a eu un problème dans la recherche des gammes équivalentes. Elles seront vides.");
                       $(".btn-yes-lbc").html("OK");
                
                        var partie5 = "Même gamme : \n\n";
                        var partie6 = "Véhicule visible sur notre parc occasion, situé au  :\n3 rue des Lurons\nPôle commercial Jeanne d’Arc\n54200 Dommartin les Toul\n(Au dessus du magasin BUT)\nOuvert du mardi au samedi de 9h30-12h00 et 14h00-18h30.\n\nTéléphone : 07 60 24 62 29\n\nNous recevons énormément de mails, il est donc préférable de prendre contact par téléphone ou de passer nous rendre visite. A bientôt !\n\n* Sous réserve d'erreur dans la liste d'options.";

                        var copyLbc = partie1 + partie2 + partie3 + partie4 + partie5 + partie6;
                
            
                        $(".btn-yes-lbc").on("click", function(){
                        freeze.addClass('hidden');
                        $(".modal-lbc").addClass('hidden');

                        var $temp = $("<textarea>");
                        var brRegex = /<br\s*[\/]?>/gi;
                        $("body").append($temp);
                        $temp.val(copyLbc.replace(brRegex, "\r\n")).select();
                        document.execCommand("copy");
                        $temp.remove();
 
                   });
                   }
    });
  
});





//Page info





var template = $("#template").text();
$("#"+template).removeClass('hidden');
 $('div[data-blc="'+template+'"]').addClass('active');
 $('div[data-blc="'+template+'"]').find('.red').removeClass('hidden');
 $('div[data-blc="'+template+'"]').find('.white').addClass('hidden');
 


 $('.service-vignette').hover(function () {
            if (!$(this).hasClass('active')){
                $(this).addClass('active-temp');
                $(this).find('.red').removeClass('hidden');
                $(this).find('.white').addClass('hidden');
        }
        }, function () {
            if (!$(this).hasClass('active')){
                $(this).removeClass('active-temp');
                $(this).find('.red').addClass('hidden');
                $(this).find('.white').removeClass('hidden');
            }
        });



$('div[data-blc]').on('click', function(){
        
   var bloc = $(this).data('blc');
   $('.template-infos').addClass('hidden');
   $("#"+bloc).removeClass('hidden');
   
   $('div[data-blc]').find('.red').addClass('hidden');
   $('div[data-blc]').removeClass('active');
   $('div[data-blc]').removeClass('active-temp');
   $('div[data-blc]').find('.white').removeClass('hidden');
   $(this).addClass('active');
   $(this).find('.red').removeClass('hidden');
   $(this).find('.white').addClass('hidden');

});





// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {
    if (input.files) {
        var pics = input.files;
        $.each(pics, function(index, pic){
            var reader = new FileReader();

            reader.onload = function(event) {     
                
                if(window.location.href.indexOf("edit") > -1) {
                    var cross = '../../../../web/img/cross.png';
                }else{
                    var cross = '../../../web/img/cross.png';
                }
                
                $($.parseHTML('<div>')).addClass('divdelcarpic').attr('id', 'carpic'+index).appendTo(placeToInsertImagePreview);
                $($.parseHTML('<img>')).attr('src', event.target.result).addClass('carpic').appendTo('#carpic'+index);
                $($.parseHTML('<img>')).attr('src', cross).addClass('delcarpic').addClass('pointer').attr('id', 'pic'+index).appendTo('#carpic'+index);                
                $($.parseHTML('<label>')).attr('for', 'lemairebundle_car_mainphoto_'+index).text('Principale').addClass('checkmain-label').appendTo('#carpic'+index);
                $($.parseHTML('<input>')).attr('type', 'checkbox').attr('id','lemairebundle_car_mainphoto_'+index).attr('name','lemairebundle_car[newpics]['+index+'][main]').attr('value', index).addClass('checkmain').appendTo('#carpic'+index);
                $($.parseHTML('<input>')).attr('type', 'hidden').attr('id','newpic'+index).attr('name','lemairebundle_car[newpics]['+index+'][name]').attr('value', pic.name).appendTo('#carpic'+index);
               
                $('.checkmain').on('click', function(){
                    $('.checkmain').prop('checked',false);
                    $(this).prop('checked',true);  
                });
                
                $('.delcarpic').on('click', function(){
                    var id = $(this).attr('id');
                    $('#car'+id).remove();
                });
            };
            reader.readAsDataURL(input.files[index]);
         
        });
    }
};

$('#my_upload').on('change', function() {
    $('div.gallery').html('');
    imagesPreview(this, 'div.gallery');
});

$('.delcarpic').on('click', function(){
                    var id = $(this).attr('id');
                    $('#car'+id).remove();
});
$('.checkmain').on('click', function(){
                    $('.checkmain').prop('checked',false);
                    $(this).prop('checked',true);  
});


// Page SHOW

$('.fichecar-arrow-left img').on('click', function(){
    var altCurrent = $('.fichecar-photo-main img').attr('alt');
    var idImgCurrent = $('.fichecar-photos-small-scroll').find('img[alt="'+altCurrent+'"]').prop('id');
    
    var idImgBack = parseInt(idImgCurrent) - 1;
    if (idImgBack === 0){
        idImgBack = $('.fichecar-photos-small-scroll img').length;
    }
    var urlImgBackSmall = $('.fichecar-photos-small-scroll').find('img[id="'+idImgBack+'"]').prop('src');
    var altImgBackSmall = $('.fichecar-photos-small-scroll').find('img[id="'+idImgBack+'"]').prop('alt');
    var urlImgBackBig1 = urlImgBackSmall.replace('thumbs','big');
    var urlImgBackBig = urlImgBackBig1.replace('_small','');
    
    $('.fichecar-photo-main').html('<img src="'+urlImgBackBig+'" alt="'+altImgBackSmall+'">');   
    
});

$('.fichecar-arrow-right img').on('click', function(){
    var altCurrent = $('.fichecar-photo-main img').attr('alt');
    var idImgCurrent = $('.fichecar-photos-small-scroll').find('img[alt="'+altCurrent+'"]').prop('id');
    
    var idImgNext = parseInt(idImgCurrent) + 1;
    var idImgLast = $('.fichecar-photos-small-scroll img').length;
    if (idImgNext > idImgLast){
        idImgNext = 1;
    }
    var urlImgNextSmall = $('.fichecar-photos-small-scroll').find('img[id="'+idImgNext+'"]').prop('src');
    var altImgNextSmall = $('.fichecar-photos-small-scroll').find('img[id="'+idImgNext+'"]').prop('alt');
    var urlImgNextBig1 = urlImgNextSmall.replace('thumbs','big');
    var urlImgNextBig = urlImgNextBig1.replace('_small','');
    
    $('.fichecar-photo-main').html('<img src="'+urlImgNextBig+'" alt="'+altImgNextSmall+'">');   
    
});

$('.fichecar-photos-small-scroll img').on('click', function(){
   var altClicked = $(this).prop('alt');
   var urlClicked = $(this).prop('src');
   var urlClickedBig1 = urlClicked.replace('thumbs','big');
   var urlImgNextBig = urlClickedBig1.replace('_small','');
   
   $('.fichecar-photo-main').html('<img src="'+urlImgNextBig+'" alt="'+altClicked+'">'); 
    
});

var selectOptions = function(){
    $('.new_car_options_list').on('click', function(event){
        console.log($(this));
        if($(this).hasClass('greenlight')) {
            $(this).removeClass('greenlight');
        } else {
            $(this).addClass('greenlight');
        };
        unselectOptions();
        event.stopImmediatePropagation();
    });
};

var unselectOptions = function(){
    $('.options_selected').on('click', function(event){
        if($(this).hasClass('redlight')) {
            $(this).removeClass('redlight');
        } else {
            $(this).addClass('redlight');
        };
        selectOptions();
        event.stopImmediatePropagation();
    });
};

$('#addoption').on('click', function(event){
    var selectedOptions = $('#unselectedoptions .greenlight');
    var nums = $(".options_selected").map(function() {
        return $(this).data('num');
    }).get();
    if (nums.length > 0) {
        var maxnum = Math.max.apply(Math, nums);
    } else {
        var maxnum = 0;
    }
    selectedOptions.each(function(key ,option){
        var dataNum = key + maxnum + 1;
        option.remove();
        $('#selectedoptions').append('<div class="options_selected" data-num="'+ dataNum +'">'+ option.innerText + '</div>');
    });
    event.stopImmediatePropagation();
    unselectOptions();
    selectOptions();
});

$('#removeoption').on('click', function(event){
    var unselectedOptions = $('#selectedoptions .redlight');
    unselectedOptions.each(function(key ,option){
        option.remove();
        $('#unselectedoptions').append('<div class="new_car_options_list">'+ option.innerText + '</div>');
    });          
    event.stopImmediatePropagation();
    unselectOptions();
    selectOptions();
});

$('#upoption').on('click', function(event){
    console.log('UP');
    var optiontoup = $('#selectedoptions .redlight');
    optiontoup.each(function(key ,option){
       var before = $(this).prev();
       if (before.length > 0) {
       $(this).insertBefore(before);
       before.attr('data-num', $(this).attr('data-num'));
       $(this).attr('data-num', $(this).attr('data-num')-1);
        }
    });           
    event.stopImmediatePropagation();
    unselectOptions();
    selectOptions();
});

$('#downoption').on('click', function(event){
    console.log('DOWN');
    var optiontodown = $('#selectedoptions .redlight');
    optiontodown.each(function(key ,option){
       var after = $(this).next();
      if (after.length > 0 ) {
       $(this).insertAfter(after);
       after.attr('data-num', $(this).attr('data-num'));
       $(this).attr('data-num', parseInt($(this).attr('data-num'))+1);
      }
    });          
    event.stopImmediatePropagation();
    unselectOptions();
    selectOptions();
});

unselectOptions();
selectOptions();

