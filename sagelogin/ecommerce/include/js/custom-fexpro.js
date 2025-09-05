$('#userLogout').click(function(){
	$.ajax({
	    url: "logout.php?argument=logOut",
	    success: function(data){
	        window.location.href = data;
	    }
	});
});


jQuery('.user-profile .dropdown a.dropdown-toggle').click(function(e){
                
	jQuery('.user-profile .dropdown').find('.dropdown-menu').toggle();
	
});