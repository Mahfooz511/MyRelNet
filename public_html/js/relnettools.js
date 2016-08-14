$(function(){ // on dom ready

	var relnet_com = 'http://'+ window.location.host +'/RelNet/public_html/' ;
	//var relnet_com = 'http://'+ window.location.host +'/' ;

		
	// Set height of window
	var init_cont_height = $("#contentarea").height() ;
	// console.log("init_cont_height " + init_cont_height);
	// console.log("window.height " + $( window ).height());
	winresize();
	$( window ).resize(function() {
  		winresize();
	});
	function winresize(){
		var rem_cont_height = $( window ).height() - $("#navigation").height() - $("footer").height() ;
		var temp_height = rem_cont_height ;
		// console.log(">> rem_cont_height" + rem_cont_height);
		// console.log("main-navigation" + $("#main-navigation").height() );
		if (init_cont_height <  $("#main-navigation").height() )
		{
			rem_cont_height =  $("#main-navigation").height() + 10 ; //120, 90
		} else if(rem_cont_height < init_cont_height) {
			rem_cont_height = init_cont_height ;
		}
		if($( window ).height() > (rem_cont_height + $("#navigation").height() + $("footer").height()))
		{
			rem_cont_height = temp_height ;
		}

  		$('#maincontentarea').height(rem_cont_height);
	}

	// join family - get members
	$('#firstfamily, #secondfamily, #splitfamily').change(function(){
		var myitem = "" ;
		if($(this).get(0).id == 'firstfamily'){
			 myitem = $("#firstmember") ;
		}else if($(this).get(0).id == 'secondfamily'){
			 myitem = $("#secondmember") ;
		}else if($(this).get(0).id == 'splitfamily'){
			myitem = $("#secondmember, #firstmember") ;
		}

		$.get($('head base').attr('href') + "/members" ,{famid: $(this).val()},
		 	function(data) {
		 		$(myitem).empty();	
				for(var i = 0; i < data.length; i++) {
				    var opt = document.createElement('option');
				    myitem.append($("<option />").val(data[i].id).text(data[i].name));
				}
		    }
		);
	});

	// find member, and find relation on the graph popup Form
	$("#memberfind a, #relationfind a").click(function(){
		event.preventDefault();
		//console.log( $(this).parent().attr("id") );
		var myitem = $(this).parent().attr("id")  ;
		$("#lightbox_content_m").empty();
		$("#lightbox_content_m2").empty();
		$("#lightbox_title").empty() ;
		if(myitem  == 'memberfind'){
			$("#lightbox_title").append("Find Person");
		}else{
			$("#lightbox_title").append("Find Relations Between");
		}
		$.get(relnet_com + "/family/person/find",{famid: family_id},
			function(data){
				$("#lightbox_m").show();					
				var s = $('<select id="membersfind"/>');
				for(var i = 0; i < data.length; i++) {
				     var opt = document.createElement('option');				     
				     s.append($("<option />").val(data[i].id).text(data[i].name));
				}
				s.appendTo("#lightbox_content_m");
				// for find relation form only	
				if(myitem  == 'relationfind'){
			 		$( "#lightbox_content_m2" ).append("<label for='relationsfind'>And</label></br>");
			 		var s2 = $('<select id="relationsfind" style="display:inline-block;vertical-align:top;"/>');
					for(var i = 0; i < data.length; i++) {
				    	var opt = document.createElement('option');				     
				     	s2.append($("<option />").val(data[i].id).text(data[i].name));
					}
					s2.appendTo("#lightbox_content_m2");
					$("#lightbox_m").addClass("findrel");
				}
				else{
					$("#lightbox_m").removeClass("findrel");
				}
					
				$("#membersfind, #relationsfind").combobox();
			}
		);
	});
	// close pop up forms
	$(".lightbox .form-panel-heading a span").click(function(){
		event.preventDefault();
		$("#lightbox_m").hide();
	});


	// Auto suggest for city/state/country
	function rhtmlspecialchars(str) {
		 if (typeof(str) == "string") {
		  str = str.replace(/&gt;/ig, ">");
		  str = str.replace(/&lt;/ig, "<");
		  str = str.replace(/&#039;/g, "'");
		  str = str.replace(/&quot;/ig, '"');
		  str = str.replace(/&amp;/ig, '&'); /* must do &amp; last */
		  }
		 return str;
	}
	if (typeof locationdata != 'undefined') {
		var locdata =   JSON.parse(rhtmlspecialchars(locationdata) ) ;
		//console.log(locdata);
		var cities = new Array() ;
		var states = new Array() ;
		var countries = new Array() ;
		var lochash = new Array();
		for (i = 0, j = 0, k = 0, l = 0 ; i < locdata.length ; i++) { 
	    	if(locdata[i].city  		!= null) cities[j++] 	= locdata[i].city ;
	    	if(locdata[i].state			!= null) states[k++] 	= locdata[i].state ;
	    	if(locdata[i].country 		!= null) countries[l++] = locdata[i].country ;
	    	lochash[i] = new Array();
	    	lochash[i][0] = locdata[i].city ;
	    	lochash[i][1] = locdata[i].state ;
	    	lochash[i][2] = locdata[i].country ;
		}		
		//console.log( jQuery.unique(states) );
		cities = jQuery.unique(cities) ;
		states = jQuery.unique(states) ;
		countries = jQuery.unique(countries) ;
	    $( "#city" ).autocomplete({
	      source: jQuery.unique(cities)
	    });
	    $( "#state" ).autocomplete({
	      source: jQuery.unique(states)
	    });
	    $( "#country" ).autocomplete({
	      source: jQuery.unique(countries)
	    });

	    $( "#city" ).focusout(function() {
	    	var interesting = lochash.filter(function(value,index) {
	    										return value[0]== $( "#city" ).val();
	    								});
	    	$( "#state" ).val( interesting[0][1] );
	    	$( "#country" ).val( interesting[0][2] );
	    });

	    $( "#state" ).focusout(function() {
	    	var interesting = lochash.filter(function(value,index) {
	    										return value[1]== $( "#state" ).val();
	    								});
	    	if($( "#state" ).val() !== "" ) $( "#country" ).val( interesting[0][2]) ;
	    });
	 }

	// on change of member reload Edit page with member data
	$("#editmemberlist").change(function(){
		var url = $('head base').attr('href') + "/" +   this.value ;
		$(location).attr('href',url);
	});

	// member info box - add data
	function getmemberinfo(selid){
		var id = $(  "#relative_id option:selected" ).val();
		$(".memberinfobox #memboxname ,.memberinfobox #memboxloc ").empty();
		$(".memberinfobox #imagerow").remove();
		$.get(relnet_com + "/family/member/info",{id: id},
			function(data){
				if(data.image != null){
					$(".memberinfobox .membox").before("<div class='col-md-2 membox' id='imagerow'></div>");
					$(".memberinfobox #imagerow").prepend('<img src='+relnet_com+'img/' + data.image +' />')
				}
				$(".memberinfobox #memboxname").append(data.name);
				if(data.city != null && data.city != ""){
					$(".memberinfobox #memboxloc").append("From " + data.city + ",");	
				}
				$(".memberinfobox #memboxloc").append(data.relative + "'s " + data.relation );	
		});
	}
	
	function getmemberinfo2(){
		var id = $( "#relative_id2 option:selected" ).val();
		$(".memberinfobox2 #memboxname , .memberinfobox2 #memboxloc ").empty();
		$(".memberinfobox2 #imagerow").remove();
		$.get(relnet_com + "/family/member/info",{id: id},
			function(data){
				if(data.image != null){
					$(".memberinfobox2 .membox").before("<div class='col-md-2 membox' id='imagerow'></div>");
					$(".memberinfobox2 #imagerow").prepend('<img src='+relnet_com+'img/' + data.image +' />')
				}
				$(".memberinfobox2 #memboxname").append(data.name);
				if(data.city != null && data.city != ""){
					$(".memberinfobox2 #memboxloc").append("From " + data.city + ",");	
				}
				$(".memberinfobox2 #memboxloc").append(data.relative + "'s " + data.relation );	
		});
	}

	if(typeof addform != 'undefined'){
		$('#relative_id, #memberlist').change(function(){
			// console.log($(this).get(0).id);
			getmemberinfo("#"+$(this).get(0).id);

		} );
		getmemberinfo();

	}
	if(typeof addrelation != 'undefined'){

		$('#relative_id2').change(function(){
			getmemberinfo2();
		} );
		getmemberinfo2();

	}
	

	$( ".nav--drop > a" ).click(function( event ) {
	  event.preventDefault();
	  $(this).next().slideToggle();
	  $(this).children("span").toggleClass("glyphicon-plus" ) ;
	  $(this).children("span").toggleClass("glyphicon-minus") ;
	  winresize() ;
	});

	// Add Member form, delete member form
 //  	$('#memberlist, #delmemberlist').change(function(){
	// 	$.get($('head base').attr('href') + "/../../../../relative/dropdown",{option: $(this).val()},
	// 		function(data) {
	// 			$(".relativeinfo").empty();
	// 			if (data.relation0.length != 0 && data.relation0 !== undefined) {
	// 				$(".relativeinfo").append( + data.relation0 + " of " + data.relname0) ;
	// 			}
	// 			if (data.relation1.length != 0 && data.relation1 !== undefined) {
	// 				$(".relativeinfo").append(", " + data.relation1 + " of " + data.relname1) ;
	// 			}
	// 			if (data.location.length != 0 && data.location !== undefined) {
	// 				$(".relativeinfo").append(", live in " + data.location) ; 				
	// 			}
	// 	    }
	// 	);
	// });

	// Delete Member Form
	$('#delmemberlist').change(function(){
		$(".deleteinfo").empty();
		$.get($('head base').attr('href') + "/../../../../members/deleteinfo",{option: $(this).val(), famid: famid },
			function(data) {
				var obj = JSON.parse(data);
				if(obj.connector){
					$(".deleteinfo").append("<h2>This Person is connecting 2 families hence cant be deleted</h2>");
					$(".deleteinfo").append("<h3>1. delete all connected members you want to delete</h3>");
					$(".deleteinfo").append("<h3>2. Split family from this person</h3>");
					$(".btn").prop('disabled', true);
				}
				else { $(".btn").prop('disabled', false);
				}

				$(".deleteinfo").append("This person is connected to following people");

				for(var key in obj.neighbours){
		            var name = obj.neighbours[key];
		            $(".deleteinfo").append("<p>"+name+"</p>");
        		}
		    }
		);
	});

	// bring back the side menu
	$(".sidebarmin").click(function(){
		event.preventDefault();
		$(".sidebarmin").hide();
		// $("#main-navigation").show();
		$(".main-menu").show();
		// $("#boxhome, #forms").addClass("sidemenumargin");
	});

	// minimize the side menu 
	$("#sidebarclose").click(function(){
		event.preventDefault();
		$(".sidebarmin").show();
		// $("#main-navigation").hide();
		$(".main-menu").hide();
		$("#boxhome, #forms").removeClass("sidemenumargin");
	});

	// find member show on graph (after popup form ). Find Relation show on graph
	if(typeof GLOBAL_elements == 'undefined'){
	  $("#submit").click(function(){    
	    $("#lightbox_m").hide();

	    var memberid = $( "#membersfind option:selected" ).val(); 
	    var url = relnet_com+"/family/" + family_id + "?p=" + memberid ;
	    // if its relation find form
	    if ( $( "#relationsfind" ).length ) { 
	    	var memberid2 = $( "#relationsfind option:selected" ).val(); 
	    	var url = relnet_com +"/family/" + family_id + "?rfid1=" + memberid + "&rfid2=" +  memberid2 ;
	    }
       	$(location).attr('href',url);
	  })
	}

	// Toolbar 
	$( "#toolbar" ).draggable({ 
		drag: function( event, ui ) {
			event.stopPropagation();
		},
		start: function( event, ui ) {
			event.stopPropagation();
		}, 
		containment: "#cyid", 
		scroll: false 
	});
    $( "#toolbar, #graphlayoutul" ).mousedown(function() {
      event.stopPropagation();
    });



    //Share form Validity 
    $("#single, #group").click(function(){
		var myshare = $('#share input[name=sharetype]:checked').val();
		if(myshare == 'Single'){
			$("#validityfieldset").hide();
		}else{
			$("#validityfieldset").show();
		}
    });

    //Change access 
    $("select[id^=accesstype]").change(function(){
    	var id = $(this).attr('id');
    	var value = $(this).val() ;
    	if(value == 'owner'){
    		$(this).prop('disabled', 'disabled');
    		$("select[id^=accesstype]").each(function(){
    			if($(this).attr('id') != id){
    				if($(this).val() == 'owner'){
    					$(this).val('edit');
    					$(this).prop('disabled', false);
    				}
    			}
    		});
    	}
    });	

    // // Change access form: enable select
    $("#access").submit(function(event){	
    	$("select").removeProp('disabled');
    });

    



	///////////////////////////
	$(".dropdown img.flag").addClass("flagvisibility");

            $(".dropdown dt a").click(function() {
                $(".dropdown dd ul").toggle();
            });
                        
            $(".dropdown dd ul li a").click(function() {
                var text = $(this).html();
                //$(".dropdown dt a span").html(text);
                $("#selected").html(text);
                $(".dropdown dd ul").hide();
                saveSelectedLang(text) ;
                // here comes the selected value
                //$("#result").html("Selected value is: " + getSelectedValue("sample"));
            });
                        
            function getSelectedValue(id) {
                return $("#" + id).find("dt a span.value").html();
            }

            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("dropdown"))
                    $(".dropdown dd ul").hide();
            });

            $.ajaxSetup({
   				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
			});
			
            function saveSelectedLang(lang) { 
            	// $.get("localhost/RelNet/public/preferrence/lang",{lang: lang},function(data){
            	// 	alert(data);
            	// });
				$.post(relnet_com+"/preferrence/lang",{lang: lang},function(data){
            		// consol.log(data);
            	});
            }


            // $("#flagSwitcher").click(function() {
            //     $(".dropdown img.flag").toggleClass("flagvisibility");
            // });
    /////////////////////////////

}); // on dom ready






