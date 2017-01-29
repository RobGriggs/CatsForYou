console.log('Cats have been scrambled.');
console.log('jQuery version: '+jQuery.fn.jquery);

var imgDir = resolveImageDir();

jQuery('img').each(function(){

	var src = jQuery(this).attr('src');
	var width = jQuery(this).attr('width');
	var height = jQuery(this).attr('height');
	
	if(width == undefined || height == undefined)
	{
		var width = jQuery(this).css('width');
		var height = jQuery(this).css('height');
	}

	var newImgName = imgDir+randomCostumeCat();

	console.log(src+" w: "+width+" h: "+height);

	if(width == undefined || height == undefined)
	{
		return true;
	}

	jQuery(this).attr('src', newImgName);
	jQuery(this).css('width', width);
	jQuery(this).css('height', height);
});

//SUPER BRITTLE... 
function resolveImageDir(){
	var imageDirKinda = document.currentScript.src;
	var needle = "CatsForYou/";
	var pos = imageDirKinda.search(needle);
	var parentDir = imageDirKinda.slice(0, pos);
	var imgDir = parentDir+needle+"CostumeCats/";	
	return imgDir;
}

function randomCostumeCat()
{
	var min = Math.ceil(1);
  	var max = Math.floor(22);
    var imgNumber = Math.floor(Math.random() * (max - min)) + min;

    return imgNumber+".jpg";
}