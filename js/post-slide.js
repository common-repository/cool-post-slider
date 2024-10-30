jQuery(document).ready(function (e) {
	jQuery('.act-post-slide').children('li:first-child').show();
	slide_arr = jQuery('.act-post-slide li').map( function( ) {
				  return jQuery(this);
				}).get();
	var lengthImages = slide_arr.length; // Length of the image list
	var CurrImage = 0; // Keep current Image index
	var widthImg = 200; // Width of a image in the list 
	var interval = 5000;
	//var BottomLength = 4; // How many images currently shows in the bottom slide
	var IndexDiff;  //This variable is used in the bottom slider click event 
	
	setInterval(post_slide, interval);
	
	function post_slide(){
		var cimg = CurrImage;
		var nimg = (CurrImage+1)%lengthImages;
		
		jQuery('.act-post-slide').children('li:nth-child('+(cimg+1)+')').fadeOut();
		jQuery('.act-post-slide').children('li:nth-child('+(nimg+1)+')').fadeIn();
		
		CurrImage = nimg;
		
	}
});
