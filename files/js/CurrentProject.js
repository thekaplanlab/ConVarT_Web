/*

    
   _____                          _     _____           _           _   
  / ____|                        | |   |  __ \         (_)         | |  
 | |    _   _ _ __ _ __ ___ _ __ | |_  | |__) | __ ___  _  ___  ___| |_ 
 | |   | | | | '__| '__/ _ \ '_ \| __| |  ___/ '__/ _ \| |/ _ \/ __| __|
 | |___| |_| | |  | | |  __/ | | | |_  | |   | | | (_) | |  __/ (__| |_ 
  \_____\__,_|_|  |_|  \___|_| |_|\__| |_|   |_|  \___/| |\___|\___|\__|
                                                      _/ |              
                                                     |__/               
    CURRENT PROJECT v0.1
    Jan, 2019

*/

/* Menu */
$('.button-collapse').sideNav();
$(".dropdown-button").dropdown({hover:false});
$('ul.tabs').tabs();
$('select').material_select();
$('.modal').modal();

$(document).ready(function(){

  // TABLE SORTING
  $('th').click(function(){
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
  })
  function comparer(index) {
    return function(a, b) {
    var valA = getCellValue(a, index), valB = getCellValue(b, index)
    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
  }
  function getCellValue(row, index){ return $(row).children('td').eq(index).text() }

  /*ClinVar Quick Search 
  $("#quick_search_clinvar").on("keyup", function() {
    let value = $(this).val().toLowerCase();
    let inputValue = $(this).val();
    var sonucSayi = $(".list-clinvar-variation").length;

    $(".list-clinvar-variation").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      if ($(this).text().toLowerCase().indexOf(value) == -1) {
      sonucSayi--;
      };
    });

    if (sonucSayi > 0) { $("#noResultQS_Clinvar").css('display', 'none'); }
    if (sonucSayi == 0) { $("#noResultQS_Clinvar").attr('style', 'display:table-row !important'); }
    if (value === "") { $("#noResultQS_Clinvar").css('display', 'none'); }
  }); */


  /*gnomAD Quick Search 

  $("#quick_search_gnomAD").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-gnomAD-variation").length;

  $(".list-gnomAD-variation").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_gnomAD").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_gnomAD").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_gnomAD").css('display', 'none'); }
  }); */

  // Domains Quick Search 

  $("#quick_search_domains").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-domain").length;

  $(".list-domain").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_domains").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_domains").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_domains").css('display', 'none'); }
  });

  //Diseases Quick Search 

  $("#quick_search_diseases").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-dis-gene").length;

  $(".list-dis-gene").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_diseases").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_diseases").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_diseases").css('display', 'none'); }
  });

    // Downloads Quick Search 

  $("#quick_search_downloads").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-download").length;

  $(".list-download").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_downloads").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_downloads").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_downloads").css('display', 'none'); }
  });

  // Downloads table 2

   $("#quick_search_downloads2").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-download2").length;

  $(".list-download2").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_downloads2").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_downloads2").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_downloads2").css('display', 'none'); }
  });

     // Downloads table 3

   $("#quick_search_downloads3").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-download3").length;

  $(".list-download3").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_downloads3").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_downloads3").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_downloads3").css('display', 'none'); }
  });

     // Downloads table 4

   $("#quick_search_downloads4").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".list-download4").length;

  $(".list-download4").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_downloads4").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_downloads4").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_downloads4").css('display', 'none'); }
  });

  // FAQ Quick Search 

  $("#quick_search_faq").on("keyup", function() {
  let value = $(this).val().toLowerCase();
  let inputValue = $(this).val();
  var sonucSayi = $(".faq_question").length;

  $(".faq_question").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) == -1) {
    sonucSayi--;
    };
  });

  if (sonucSayi > 0) { $("#noResultQS_faq").css('display', 'none'); }
  if (sonucSayi == 0) { $("#noResultQS_faq").attr('style', 'display:table-row !important'); }
  if (value === "") { $("#noResultQS_faq").css('display', 'none'); }
  });


});

// Spemud search autocomplete
$(document).ready(function() {

	// Ajax for human proteins
	$('#human_prot_id').autocomplete({
		//data = JSONfromPHP,
		data: { "TP53 - NP_123": null, "TP53 - NP_234": null, "TP53 - XP_456": null, },
		limit: 20,
		onAutocomplete: function(val) {},
		limit: 20,
		minLength: 1, 
	});

	// Ajax for mouse proteins
	$('#mouse_prot_id').autocomplete({
		//data = JSONfromPHP,
		data: { "ARL13B - NP_9123": null, "ARL13B - NP_5234": null, "ARL13B - XP_7456": null, },
		limit: 20,
		onAutocomplete: function(val) {},
		limit: 20,
		minLength: 1, 
	});

	// Ajax for C. elegans proteins
	$('#cel_prot_id').autocomplete({
		//data = JSONfromPHP,
		data: { "osm5 - NP_021": null, "osm5 - NP_034": null, "osm5 - XP_0456": null, },
		limit: 20,
		onAutocomplete: function(val) {},
		limit: 20,
		minLength: 1, 
	});

});



// Ajax for human proteins
  // $(document).ready(function() {
    // $.getJSON('/get_protein_lists.php?species=hsa', function(data) {
    //     // Create skills object to hold skill names
    //     var skills = new Object();
    //     data.forEach(function(skill_list) {
    //         skills[skill_list.skill_name] = null;
    //     });
        // Protein IDs and Gene names autocomplete
        // $('.chips').chips();
    //     $('.chips-autocomplete').chips({
    //         placeholder: 'Type a gene name or protein ID',
    //         autocompleteOptions: {
    //             data: skills,
    //             limit: 1,
    //             minLength: 1
    //         }
    //     });
    // });
  //   return false;
  // });