$(document).on('click','#searchBtn',function(){
    location.href=$(this).attr('data-link') + '/' +$('#searchTerm').val();
});