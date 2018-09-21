$(function() {
  
  initAutocomplete();

  $('#stationNumber, #stationCity, #stationRegion, #stationStreet').on('focusout', function(e){
    changeAutocompleteForm(e, false);
  });
  
  $('#stationNumber, #stationCity, #stationRegion, #stationStreet').on('keyup', function(e){

    if( e.keyCode==8 && e.currentTarget.value.length === 0 ) {
      
      $('#stationNumber').val('');
      $('#stationCity').val('');
      $('#stationRegion').val('');
      $('#stationStreet').val('');
      
    }
    
    changeAutocompleteForm(e, false);
    
  });             
    
});


function initAutocomplete() {
    
  var response = [];
  var availableNumbers = [];
  var availableCities = [];
  var availableRegions = [];
  var availableStreets = [];      
    
  $.post('/api/station/getall')
  .done(function(data)  {     
    $.each(data.data.cities,function(index,value) {
      availableCities.push(value.city_azs);
    });
    $.each(data.data.regions,function(index,value)  {
      availableRegions.push(value.region_azs);
    });
    $.each(data.data.numbers,function(index,value) { 
      availableNumbers.push(value.number_azs);
    });
    $.each(data.data.streets,function(index,value) { 
      availableStreets.push(value.street_azs);
    });       
    $("#stationNumber, #stationCity, #stationRegion, #stationStreet").prop('disabled', false);
    
  })
  .fail(function(data) {      
      $("#stationNumber, #stationCity, #stationRegion, #stationStreet").prop('disabled', true);
      $('#systemMessage').css('color', 'red');
      $('#systemMessage').html(data.responseJSON.message);
  });    
  
  $("input[name=stationNumber]").autocomplete({
    source: availableNumbers,
    minLength: 0
  }).focus(function () {
    $(this).autocomplete("search");
  });
  
  $("input[name=stationCity]").autocomplete({
    source: availableCities,
    minLength: 0
  }).focus(function () {
    $(this).autocomplete("search");
  });
  
  $("input[name=stationRegion]").autocomplete({
    source: availableRegions,
    minLength: 0
  }).focus(function () {
    $(this).autocomplete("search");
  });
  
  $("input[name=stationStreet]").autocomplete({
    source: availableStreets,
    minLength: 0
  }).focus(function () {
    $(this).autocomplete("search");
  });      
  
}

function changeAutocompleteForm(e, remove) {
  
  var response = [];
  var availableNumbers = [];
  var availableCities = [];
  var availableRegions = [];
  var availableStreets = [];
  
  var selectedNumber = $('#stationNumber').val();
  var selectedCity = $('#stationCity').val();
  var selectedRegion = $('#stationRegion').val();
  var selectedStreet = $('#stationStreet').val();  
  
  if( selectedNumber.length === 0 
    || selectedCity.length === 0 
    || selectedRegion.length === 0
    || selectedStreet.length === 0 ) {    
    
    $.post('/api/station/getremain', { 
      number: selectedNumber, 
      city: selectedCity,
      region: selectedRegion,
      street: selectedStreet 
    })
    .done(function(data){                                           

        $.each(data.data.cities,function(index,value) {
          availableCities.push(value.city_azs);
        });
        if( data.data.cities.length == 1 ) {
          if(selectedCity.length === 0) $("#stationCity").val(availableCities[0]);          
        }       
                
        $.each(data.data.regions,function(index,value)  {
            availableRegions.push(value.region_azs);
        });
        if( data.data.regions.length == 1 ) {
          if(selectedRegion.length === 0) $("#stationRegion").val(availableRegions[0]);
        }
        
        $.each(data.data.numbers,function(index,value) { 
          availableNumbers.push(value.number_azs);
        });                
        if( data.data.numbers.length == 1 ) {
          if(selectedNumber.length === 0) $("#stationNumber").val(availableNumbers[0]);          
        }
        
        $.each(data.data.streets,function(index,value) { 
          availableStreets.push(value.street_azs);
        });                
        if( data.data.streets.length == 1 ) {
          if(selectedStreet.length === 0 ) $("#stationStreet").val(availableStreets[0]);          
        }                       

        $("#stationNumber, #stationCity, #stationRegion, #stationStreet").prop('disabled', false);        
      
    })
    .fail(function(data) {      
        $("#stationNumber, #stationCity, #stationRegion, #stationStreet").prop('disabled', true);
        $('#systemMessage').css('color', 'red');
        $('#systemMessage').html(data.responseJSON.message);
    });
    
    $("input[name=stationNumber]").autocomplete({
      source: availableNumbers,
      minLength: 0
    }).focus(function () {
      $(this).autocomplete("search");
    });
    
    $("input[name=stationCity]").autocomplete({
      source: availableCities,
      minLength: 0
    }).focus(function () {
      $(this).autocomplete("search");
    });
    
    $("input[name=stationRegion]").autocomplete({
      source: availableRegions,
      minLength: 0
    }).focus(function () {
      $(this).autocomplete("search");
    });
    
    
    $("input[name=stationStreet]").autocomplete({
      source: availableStreets,
      minLength: 0
    }).focus(function () {
      $(this).autocomplete("search");
    });      
           
        
  }

}