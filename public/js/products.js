$(document).on('click','#searchBtn',function(){
	var searchTerm = $('#searchTerm').val();
	if(searchTerm == "")
		searchTerm = "all";
    location.href=$(this).attr('data-link') + '/' + searchTerm;
});