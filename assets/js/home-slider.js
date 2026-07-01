document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('[data-scouting-nederland-slider]').forEach(function(slider){
    var slides = slider.querySelectorAll('.scouting-nederland-home-slide');
    if(slides.length < 2) return;
    var current = 0;
    setInterval(function(){
      slides[current].classList.remove('is-active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('is-active');
    }, 6000);
  });
});
