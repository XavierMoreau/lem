// Chargement de la page principale - Cascade des vignettes
$(window).on('load', function(){
	$(".vignette").delay(200).each(function(i){
		$(this).delay(200*i).queue(function(){
			$(this).addClass("show");
		})
	})
});

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
	}else{
            $(".vignette").removeClass('hidden-brand');
		
	}
});

var priceSelect = $(".select-price");
priceSelect.on('change', function() {
	var priceValue = $(this).find('select').val();
        var priceMax = $(this).find('select').find('option:selected').attr('data-max');
	if (priceValue !== ""){	
		$(".vignette").each(function(){
			var priceHtml = $(this).find('.vignette-petite').find('.vignette-price').html();
                        var price = priceHtml.replace('€','');
                        
			if (priceValue <= price && price <= priceMax){
                            
				$(this).removeClass('hidden-price');
                    
			}else{
				$(this).addClass('hidden-price');
			}
		});
	}else{
		$(".vignette").removeClass('hidden-price');
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
var prixdestock = $('#lemairebundle_car_prixdestock');
var prixgarantie = $('#lemairebundle_car_prixgarantie');
var vendu = $('#lemairebundle_car_vendu');
var promotion = $('#lemairebundle_car_promotion');
var active = $('#lemairebundle_car_active');
var photos = $('.newcar-photos-small-zone');

//désactivation du bouton enregistrer :
var saveButton = $('.btn-save');

if ((marque.val() !== "" || addMarqueZone.val() !== "")&&
    (modele.val() !== "" || addModeleZone.val() !== "")    
        ){
    saveButton.attr('disabled', false);
    
}
// désactivation de la case Visible sur le site

//var activeCheck = $('#lemairebundle_car_active');
//if ((marque.val() !== "" || addMarqueZone.val() !== "")&&
//    (modele.val() !== "" || addModeleZone.val() !== "")  
//        ){
//    activeCheck.attr('disabled', false);
//    
//}


// Selection du modèle en fonction de la marque
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


var addOptionsButton = $('.add-option');
addOptionsButton.on('click', function() {
    var optionsCount = $('.option').find('input').length;
    var newCount = optionsCount + 1;
    $(this).before('<label for="lemairebundle_car_options_'+ newCount +'"></label><input type="text" name="lemairebundle_car[option supp]['+ newCount +']">');
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