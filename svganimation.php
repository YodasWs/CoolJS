<!DOCTYPE html>
<html lang="en-us">
<head>
<title>SVG Animation using SMIL</title>
<style type="text/css">
svg {
	width: 800px; height: 500px; border: 1px solid black; margin: 0 auto;
}
</style>
<!--[if lt IE 9]><script type="text/javascript" src="http://yodas.ws/api/html5shiv.js"></script><![endif]-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
/*
	(function() {
		$('svg:visible > circle').attr('cy', $('svg:visible > circle').attr('cy') - 1);
		if ($('svg:visible > circle').attr('cy') >= 200)
			setTimeout(arguments.callee, 10);
		else {
			$('svg:visible > circle').hide();
//			$('svg:visible > g > circle').attr('cy', $('svg:visible > circle').attr('cy'));
			$('svg:visible > g').attr('transform','translate(' + $('svg:visible > circle').attr('cx') + ',' + $('svg:visible > circle').attr('cy') + ')').show();
			var time = 0;
			(function() {
				$('svg:visible > g > circle').each(function(i) {
//					$(this).attr('cx', 
				});
			});
		}
	})();
/**/
//*
	// Copy SVG
	var startCode = $('svg').wrap('<div>').closest('div').html();
	$('svg').unwrap();
	var code = $('<code>').text(startCode);
	var hiddenSVG = $('<div>').html(
		code.text().replace(/<\/?svg.*>/g, '')
	);
	// Manipulate SVG as desired
	hiddenSVG.children('circle').attr('cx',400).attr('cy',500).html(
//		'<animateMotion begin="0s" dur="1s" repeatCount="1" rotate="none" path="M 400 500 V 200" />'
//		'<animateMotion begin="2s" dur="1s" repeatCount="1" rotate="none" path="M 400 500 V 200" />'+
'<animate attributeName="cy" from="500" to="200" begin="0s" dur="2s" repeatCount="1" fill="freeze" '+
' calcMode="spline" keyTimes="0; 1" keySplines="0 .2 .8 .9" id="rise" additive="replace" accumulate="none" />' +
'<animate attributeName="r" from="5" to="0" begin="rise.end" dur=".01s" repeatCount="1" fill="freeze" />'
	);
	hiddenSVG.find('g#burst > circle').each(function(i) {
		$(this).attr('cx',0).attr('cy',0).attr('r',0).css({
			'stroke':'none','fill':'#b8860b'
		}).html(
'<animate attributeName="cy" from="0" to="' + (Math.sin(Math.PI * 2 * i / 3) * 200) + '" begin="rise.end" dur="1s" repeatCount="1" fill="freeze" />' +
'<animate attributeName="cx" from="0" to="' + (Math.cos(Math.PI * 2 * i / 3) * 200) + '" begin="rise.end" dur="1s" repeatCount="1" fill="freeze" />' +
'<animate attributeName="r" from="0" to="5" begin="rise.end" dur=".3s" repeatCount="1" fill="freeze" />' +
'<animate attributeName="r" from="5" to="0" begin="rise.end + .9s" dur=".1s" repeatCount="1" fill="freeze" id="fade' + i + '" />' +
'<animateColor attributeName="fill" to="' + (function(i) {
		switch (i) {
			case 0: return '#dc143c'; // Crimson
			case 1: return '#1e90ff'; // DodgerBlue
			default: return '#228b22'; // ForestGreen
		}
	})(i) + '" begin="rise.end" dur=".1s" repeatCount="1" fill="freeze" />' +
'<animateColor attributeName="fill" to="#000000" begin="rise.end + .6s" dur=".3s" repeatCount="1" fill="freeze" />'
		);
	}).closest('g').attr('transform','translate(400,200)');

	// Now Output SVG
	code.text(code.text().match(/<svg.*>/)[0] + hiddenSVG.html() + code.text().match(/<\/svg>/)[0]);
	$('svg').wrap('<div>').parent().html(code.text());
	$('svg').siblings().remove();
	$('svg').unwrap();
/**/
});
</script>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 800 500">
<circle r="5" style="stroke:none;fill:black">
<!--
	<animateMotion begin="0s" dur="1s" repeatCount="1" rotate="none" path="M 400 500 V 200" onend="alert('sbg was here')" />
-->
</circle>
<g id="burst">
	<circle cx="0" cy="0" r="0" style="stroke:none;fill:green"/>
	<circle cx="0" cy="0" r="0" style="stroke:none;fill:blue"/>
	<circle cx="0" cy="0" r="0" style="stroke:none;fill:red"/>
</g>
<!--
<g transform="translate(400,200)">
	<path d="M 0 0 h 50 v 50 z" style="stroke:black;fill:none"/>
	<text x="55" y="27">sin &theta;</text>
	<text x="15" y="-5">cos &theta;</text>
</g>
<path d="M 400,200 q 50,50 100,0 l -100,0 l 50,50 l 50,-50 m -50,0 l 0,50" style="stroke:black;fill:none"/>
<path d="M 400,200 A 25,50 -40 1,0 450,200" style="stroke:black;fill:none"/>
-->
</svg>
<!--
<g>
	<rect x="0" y="0" width="100" height="50" stroke="black" stroke-width="1" fill="none"/>
	<text x="10" y="30">Hello World</text>
<animateMotion begin="0s" dur="2s" repeatCount="1" rotate="auto" fill="remove"><mpath xlink:href="#pathway"/></animateMotion>
	<animateTransform attributeName="transform" begin="0s" dur="2s" type="translate"
		values="200 0;0 200;200 0" repeatCount="1.5" fill="freeze" additive="sum"
	/>
	<animateTransform attributeName="transform" begin="0s" dur="2s" type="rotate"
		from="0 50 25" to="360 50 25" repeatDur="3s" additive="sum" fill="freeze"
	/>
</g>
<path id="pathway" class="link" d="M 297 93 q 0 170 170 170" style="display:none"/>
-->
</body>
</html>