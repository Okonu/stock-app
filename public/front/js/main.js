jQuery(document).ready(function () {
    
  // Cache the jQuery object for the data-table elements
  var $dataTables = $(".data-table");
    
  // Only initialize DataTables if there are elements with the data-table class
  if ($dataTables.length) {
    $dataTables.DataTable();
  }


  $('.update-agency-btn').click((e)=>{
    e.preventDefault();

    let agency = $('#agency').val(); 
    let name   = $('#agency_name_input').val();
    let phone  = $('#agency_phone_input').val();
    let email  = $('#agency_email_input').val();
    let logo   = $('#agency_logo_input').val();
    let location = $('#agency_location_input').val();
    let description = $('#agency_description_input').val(); 

    $.post('../api/',{update_agency:agency,name:name,email:email,phone:phone,location:location,description:description,logo:logo})
     .done((response)=>{
        // console.log(response);
         if(response.success){
              $.toast({ 
                heading: 'Hurray!',
                text: response.message,
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3500, 
                stack: 6
              })
              
            window.setTimeout(() => { window.location.href = './agency_profile'; }, 4000); // Delay then redirect
         }

     })
     .fail((error)=>{
        console.log(error);
        error = error.responseJSON; 
        $.toast({ 
                text: error.message,
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'warning',
                hideAfter: 5000, 
                stack: 6
              });

     });
 
     
     

  });


  $('.add-property-btn').click((e)=>{
    e.preventDefault();

    let agency = $('#agency').val(); 
    let name   = $('#property_name').val();
    let manager= $('#property_manager').val();
    let units  = $('#property_units').val();
    let location = $('#property_location').val();
    let description = $('#property_description').val(); 

    $.post('../api/',{add_property:agency,name:name,location:location,manager:manager,units:units,description:description})
     .done((response)=>{
        // console.log(response);
        if(response.success){
              $.toast({ 
                heading: 'Hurray!',
                text: response.message,
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3500, 
                stack: 6
              })
              
            window.setTimeout(() => { window.location.href = './properties'; }, 4000); // Delay then redirect
         }

     })
     .fail((error)=>{
        console.log(error);
        error = error.responseJSON; 
        $.toast({ 
                text: error.message,
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'warning',
                hideAfter: 5000, 
                stack: 6
              });

     });

  });
  
  $('.btn-lg').click(function() {
    $(this).toggleClass('btn-info btn-default');
    // $(this).prop('disabled', function(i, val) {
    //     return !val;
    // });
  });
 
 


});