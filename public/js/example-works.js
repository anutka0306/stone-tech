$(document).ready(function () {
	jQuery('.fotorama').fotorama({
		width: '100%',
		ratio: 16 / 9,
		fit: 'cover',
		transition: 'crossfade',
		nav: 'thumbs',
		loop: true,
		thumbheight: 120,
		thumbwidth: 170,
	});
});