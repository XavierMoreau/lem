
$(window).on('load', function(){
	$(".vignette").delay(200).each(function(i){
		$(this).delay(200*i).queue(function(){
			$(this).addClass("show");
		})
	})
});


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


var marque = $('#lemairebundle_car_marque');
var modele = $('#lemairebundle_car_modele');

marque.on('change', function() {

    var marqueId = $('#lemairebundle_car_marque').val();
    modele.find('option[data-marque="' + marqueId + '"]').removeClass('hidden');
    modele.find('option[data-marque!="' + marqueId + '"]').addClass('hidden');
    modele.val('');
    
});


var addMarqueButton = $('.add-marque');
var removeMarqueButton = $('.remove-marque');
var addMarqueZone = $('#add-marque');
var selectMarque = $('#lemairebundle_car_marque');
addMarqueButton.on('click', function() {
    removeMarqueButton.removeClass('hidden');
    addMarqueButton.addClass('hidden');
    selectMarque.addClass('hidden');
    addMarqueZone.removeClass('hidden');
});
removeMarqueButton.on('click', function() {
    removeMarqueButton.addClass('hidden');
    addMarqueButton.removeClass('hidden');
    selectMarque.removeClass('hidden');
    addMarqueZone.addClass('hidden');
});


var addModeleButton = $('.add-modele');
var removeModeleButton = $('.remove-modele');
var addModeleZone = $('#add-modele');
var selectModele = $('#lemairebundle_car_modele');
addModeleButton.on('click', function() {
    removeModeleButton.removeClass('hidden');
    addModeleButton.addClass('hidden');
    selectModele.addClass('hidden');
    addModeleZone.removeClass('hidden');
});
removeModeleButton.on('click', function() {
    removeModeleButton.addClass('hidden');
    addModeleButton.removeClass('hidden');
    selectModele.removeClass('hidden');
    addModeleZone.addClass('hidden');
});


var addEnergieButton = $('.add-energie');
var removeEnergieButton = $('.remove-energie');
var addEnergieZone = $('#add-energie');
var selectEnergie = $('#lemairebundle_car_energie');
addEnergieButton.on('click', function() {
    removeEnergieButton.removeClass('hidden');
    addEnergieButton.addClass('hidden');
    selectEnergie.addClass('hidden');
    addEnergieZone.removeClass('hidden');
});
removeEnergieButton.on('click', function() {
    removeEnergieButton.addClass('hidden');
    addEnergieButton.removeClass('hidden');
    selectEnergie.removeClass('hidden');
    addEnergieZone.addClass('hidden');
});


var addTypeButton = $('.add-type');
var removeTypeButton = $('.remove-type');
var addTypeZone = $('#add-type');
var selectType = $('#lemairebundle_car_type');
addTypeButton.on('click', function() {
    removeTypeButton.removeClass('hidden');
    addTypeButton.addClass('hidden');
    selectType.addClass('hidden');
    addTypeZone.removeClass('hidden');
});
removeTypeButton.on('click', function() {
    removeTypeButton.addClass('hidden');
    addTypeButton.removeClass('hidden');
    selectType.removeClass('hidden');
    addTypeZone.addClass('hidden');
});


var addOptionsButton = $('.add-option');
addOptionsButton.on('click', function() {

    var optionsCount = $('.option').find('input').length;
    var newCount = optionsCount + 1;
    $(this).before('<label for="lemairebundle_car_options_'+ newCount +'"></label><input type="text" name="lemairebundle_car[option supp_'+ newCount +']">');

});




// BOUTON UPLOAD IMAGES
    if(window.File && window.FileList && window.FileReader)
    {
        $('#files').on("change", function(event) {
            var files = event.target.files; //FileList object
            var output = document.getElementById("result");
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                //Only pics
                // if(!file.type.match('image'))
                if(file.type.match('image.*')){
                    if(this.files[0].size < 2097152){    
                  // continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load",function(event){
                        var picFile = event.target;
                        var div = document.createElement("div");
                        div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +
                                "title='preview image'/>";
                        output.insertBefore(div,null);            
                    });
                    //Read the image
                    $('#clear, #result').show();
                    picReader.readAsDataURL(file);
                    }else{
                        alert("Image Size is too big. Minimum size is 2MB.");
                        $(this).val("");
                    }
                }else{
                alert("You can only upload image file.");
                $(this).val("");
            }
            }                               
           
        });
    }
    else
    {
        console.log("Your browser does not support File API");
    }


   $('#files').on("click", function() {
        $('.thumbnail').parent().remove();
        $('result').hide();
        $(this).val("");
    });

    $('#clear').on("click", function() {
        $('.thumbnail').parent().remove();
        $('#result').hide();
        $('#files').val("");
        $(this).hide();
    });
