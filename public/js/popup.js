$(document).ready(function() { // Ждём загрузки страницы
	
	$(".popup_bg").click(function(){	// Событие клика на затемненный фон	   
		$(".popup").fadeOut(500);	// Медленно убираем всплывающее окно
		$(".popup2").fadeOut(500);	// Медленно убираем всплывающее окно
		$("html,body").css("overflow","auto");
		
	});

	$(".close").click(function(){	// Событие клика на крестик	   
		$(".popup").fadeOut(500);	// Медленно убираем всплывающее окно
		$(".popup2").fadeOut(500);	// Медленно убираем всплывающее окно
		$("html,body").css("overflow","auto");
	});

	
});


function showPopup() {
	
	$(".popup").fadeIn(500); // Медленно выводим изображение
	$("html,body").css("overflow","hidden");

}

function showPopup2() {
	$(".popup2").fadeIn(500); // Медленно выводим изображение
	$("html,body").css("overflow","hidden");
	$(".popup2").css("overflow","auto");
}




