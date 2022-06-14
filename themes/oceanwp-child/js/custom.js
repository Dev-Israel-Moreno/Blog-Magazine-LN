/*
Theme Name: OceanWP Child
Theme URI: https://oceanwp.org/
Template: oceanwp
Author: Nick
Author URI: https://oceanwp.org/about-me/
Description: OceanWP is the perfect theme for your project. Lightweight and highly extendable, it will enable you to create almost any type of website such a blog, portfolio, business website and WooCommerce storefront with a beautiful &amp; professional design. Very fast, responsive, RTL &amp; translation ready, best SEO practices, unique WooCommerce features to increase conversion and much more. You can even edit the settings on tablet &amp; mobile so your site looks good on every device. Work with the most popular page builders as Elementor, Beaver Builder, Brizy, Visual Composer, Divi, SiteOrigin, etc... Developers will love his extensible codebase making it a joy to customize and extend. Best friend of Elementor &amp; WooCommerce. Looking for a Multi-Purpose theme? Look no further! Check the demos to realize that it's the only theme you will ever need: https://oceanwp.org/demos/
Tags: two-columns,right-sidebar,footer-widgets,blog,news,custom-background,custom-menu,post-formats,rtl-language-support,sticky-post,editor-style,threaded-comments,translation-ready,buddypress,custom-colors,featured-images,full-width-template,theme-options,e-commerce,block-styles,wide-blocks,accessibility-ready
Version: 2.0.6.1619709874
Updated: 2021-04-29 15:24:34

*/

jQuery(window).scroll(function(){
    if(jQuery(document).scrollTop()>=jQuery(document).height()/10)
      
		jQuery("#side-social-icons").fadeIn(500);else jQuery("#side-social-icons").fadeOut(500);
});

//*********************************************************************************************************************

var current = 0;
var imagenes = new Array();

/*
<?php

echo 'var numImage = '.$count_authors.'';

?>
*/

/*
jQuery.ajax({
		method: "POST",
		url: "/luznocturnaweb/wp-content/themes/oceanwp-child/functions.php",
		
	})
	
	.done(function( response ){
		
	});
*/



jQuery(document).ready(function() {
	
	/*alert("Funcion");*/
	
    //var numImages = "<?php echo $count_authors; ?>";
	
	//var numImages = '.$count_authors.';
	
	//var numImages = '.$output.';
	

	
	
	
	/*
	 jQuery.ajax({
		 
		 type: "POST",
		 url: "http://localhost/luznocturnaweb/wp-content/themes/oceanwp-child/js/servidor.php",
		 data: '$result',
		 dataType: 'json',
		 sucess: function(data){
			 console.log("El valor de la fucking var es" + data.result);
			 	
		 }
		 
	 })
	 .done(function( msg ){
		 //alert("rrrrrrrrrrrrrrrrrr");
	 })
	 
	 sucess(function( data ){
		 //alert(data.result);
	 })
	 ;
	
	 jQuery.getJSON( "http://localhost/luznocturnaweb/wp-content/themes/oceanwp-child/js/servidor.php" , function (json){
		 
		 //alert('Data Loaded2 es:' + data );
		 
		 //alert('2'  );
		 
		 if(data.variable === "100"){
			 
			 //alert("LEYO LA VARIABLE 100");
			 
		 }
		 
	 })
	
	//alert ('el valor de numImages es:'+numImages);
	 
	 var numImages2 = jQuery.post( "http://localhost/luznocturnaweb/wp-content/themes/oceanwp-child/js/servidor.php", function( data ){
      
	  
	     alert('Data Loaded es:' + data );
		 
	      

	 });
	 
	 alert('numImages2 es:' + numImages2 );
	 
	
	 
	 	 alert('1'  );
	 
	
	 
	 jQuery.post( "http://localhost/luznocturnaweb/wp-content/themes/oceanwp-child/js/servidor.php",{ func: "all_columnist" }, function( data ) {
		
		
   // alert("con alert es: " + data.x);		 
		 
  
}, "text");
	 
	 //alert('numImages2 es:' + numImages2 );
	
	  numImages2.done(function( data ){
		  
		 //var content = jQuery( data ); 
		 var content =  data; 
		 
		 //alert('la variable content es:' + content );
		 
		// console.log("Por consola es: "+content);
		 
		 
		  
	  })
	  
	 
	/*
	var numImages2 = jQuery.get( "http://localhost/luznocturnaweb/wp-content/themes/oceanwp-child/functions.php", function(){
	
       alert('sucess');	
		
	})

	
	
	.done(function(){
		
		alert("sucess second");
		
	})
	.fail(function(){
		
		alert("error");
		
	})
	;
	
		*/
	
		var numImages = 4;
	
    if (numImages <= 3) {
        jQuery('.right-arrow').css('display', 'none');
        jQuery('.left-arrow').css('display', 'none');
		alert("es <= 3");
    }

    jQuery('.left-arrow').on('click',function() {
        if (current > 0) {
            current = current - 1;
        } else {
            current = numImages - 3;
			alert("left:" + current);
        }
		
		alert("funcion para ir hacia atras");

        jQuery(".carrusel").animate({"left": -(jQuery('#'+current).position().left)}, 600);

        return false;
    });

    jQuery('.left-arrow').on('hover', function() {
        jQuery(this).css('opacity','0.5');
    }, function() {
        jQuery(this).css('opacity','1');
    });

    jQuery('.right-arrow').on('hover', function() {
        jQuery(this).css('opacity','0.5');
    }, function() {
        jQuery(this).css('opacity','1');
    });

    jQuery('.right-arrow').on('click', function() {
        if (numImages > current + 3) {
            current = current+1;
			alert("right:" + current);
        } else {
            current = 0;
        }

        alert("funcion para ir hacia adelante");

        jQuery(".carrusel").animate({"left": -(jQuery('#'+current).position().left)}, 600);
		     

        return false;
    });
 });
 
 //*********************************************************************************************************************
 