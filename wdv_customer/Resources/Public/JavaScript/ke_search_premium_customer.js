$(document).ready( function() {
	jQuery("#ke_search_sword").autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: "index.php?eID=keSearchPremiumAutoComplete",
				dataType: "json",
				cache: false,
				data: {
					wordStartsWith: request.term,
					amount: 10,
					pid: 100
				},
				success: function( data ) {
					if(data) {
						response( jQuery.map( data, function( item ) {
							return { label:item, value:item }
						}))
					} else response( {} )
				}
			})
		},
		select: function( event, ui ) {
			jQuery( "#ke_search_sword" ).val( ui.item.value );
			jQuery( "form#form_kesearch_pi1" ).submit();
		},
		minLength: 1
	});
	jQuery("#ke_search_sword_mobile").autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: "index.php?eID=keSearchPremiumAutoComplete",
				dataType: "json",
				cache: false,
				data: {
					wordStartsWith: request.term,
					amount: 10,
					pid: 100
				},
				success: function( data ) {
					if(data) {
						response( jQuery.map( data, function( item ) {
							return { label:item, value:item }
						}))
					} else response( {} )
				}
			})
		},
		select: function( event, ui ) {
			jQuery( "#ke_search_sword_mobile" ).val( ui.item.value );
			jQuery( "form#form_kesearch_pi1_mobile" ).submit();
		},
		minLength: 1
	});
	jQuery("#ke_search_sword_top").autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: "index.php?eID=keSearchPremiumAutoComplete",
				dataType: "json",
				cache: false,
				data: {
					wordStartsWith: request.term,
					amount: 10,
					pid: 100
				},
				success: function( data ) {
					if(data) {
						response( jQuery.map( data, function( item ) {
							return { label:item, value:item }
						}))
					} else response( {} )
				}
			})
		},
		select: function( event, ui ) {
			jQuery( "#ke_search_sword_top" ).val( ui.item.value );
			jQuery( "form#form_kesearch_pi1_top" ).submit();
		},
		minLength: 1
	});
});