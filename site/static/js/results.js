$(document).ready(function () {
	do_pulsate=true;
    function pulsate() {
    	if(do_pulsate)
		{
	        $(".congrats").animate({
            opacity: 0.5
        	}, 700, 'linear').animate({
        	    opacity: 1
        	}, 700, 'linear', pulsate);
        }
    }
    //pulsate();
    
	function do_fade_in(){
		$(".sharing").animate({opacity:1},500,'linear');
		$(".congrats").animate({opacity:0.4},500,'linear');
		$("#score").hover(
			function(){
				//do_pulsate=false;
				$(".congrats").stop(true);
				$(".sharing").animate({opacity:1},500,'linear');
				$(".congrats").animate({opacity:0.4},500,'linear');
			},
			function(){
				//do_pulsate=true;
				$(".sharing").animate({opacity:0},500,'linear');
				$(".congrats").animate({opacity:1},500,'linear');
				//pulsate();
			}
		);
	}
	
	setTimeout(do_fade_in,5000);
});

function share(t){
	window.open(t.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=370,width=640');
	return false;
}