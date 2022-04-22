$(document).ready(function(){
    $('.side-bar').addClass('active');
    $('.menu-btn').css("visibility", "hidden");
    document.getElementById('logoPocetna').style.marginLeft = '160px';
    document.getElementById('logoPocetna').style.transition = '0.6s ease';
  //jquery for toggle sub menus
  $('.sub-btn').click(function(){
	$(this).next('.sub-menu').slideToggle();
	$(this).find('.dropdown').toggleClass('rotate');
  });

  //jquery for expand and collapse the sidebar
  $('.menu-btn').click(function(){
	$('.side-bar').addClass('active');
	$('.menu-btn').css("visibility", "hidden");
	document.getElementById('logoPocetna').style.marginLeft = '160px';
	document.getElementById('logoPocetna').style.transition = '0.6s ease';
  });

  $('.close-btn').click(function(){
	$('.side-bar').removeClass('active');
	$('.menu-btn').css("visibility", "visible");
      document.getElementById('logoPocetna').style.marginLeft = '20px';
      document.getElementById('logoPocetna').style.transition = '0.6s ease';
  });
});
