<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/underscore.min.js') }}"></script>

<script src="{{ URL::asset('js/slider/modernizr.custom.js') }}"></script>
<script src="{{ URL::asset('js/slider/jquery.cbpFWSlider.min.js?v=1') }}"></script>
<script>
	$( function() {
		$( '#cbp-fwslider' ).cbpFWSlider({ isAnimating: true});
	} );
</script>
<!--<script src="{{ URL::asset('js/jquery.bxslider.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.bxslider').bxSlider();
	});
</script>-->