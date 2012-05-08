function optionsButton(){
	//alert("Inside ShowMore!!!");
	var text = $("#more-options").text();
	//alert("text -> "+text);
	if (text == "Menos..."){
		$("#search-options").hide("slow");
		$("#more-options").text('Mas...');
		$("#more-options").val('hide');
	}else{
		$("#search-options").show("slow");
        $("#more-options").text('Menos...');
        $("#more-options").val('show');
	}

}


function importSugar(item){

	//alert('item -> '+item);
	var btn = $('#toSugar'+item);
	btn.button('loading');
	setTimeout(function(){btn.button('complete')}, 3000);
	//btn.button('complete');

}

function saveSearch(base_url){
	var btn = $('#save-search');
    btn.button('loading');
    setTimeout(function(){btn.button('complete')}, 3000);

    jQuery.ajax(base_url + '/'+document.getElementById('form_busqueda').elements['idSearch'].value);
	// Muestro alert
	setTimeout(function(){$("#myalert").show('slow');btn.hide('slow');}, 3005);
}


function search(){
	$("form");

}


