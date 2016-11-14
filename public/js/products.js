$(document).on('click','#searchBtn',function(){
	var searchTerm = $('#searchTerm').val();
	if(searchTerm == "")
		searchTerm = "all";
    location.href=$(this).attr('data-link') + '/' + searchTerm;
});
$(document).on('keypress','#searchTerm',function(e){
	if (e.which == 13) {
		$('#searchBtn').trigger('click');
        return false;
    }
});
