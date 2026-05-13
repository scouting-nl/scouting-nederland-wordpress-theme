document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('[data-tc4-slider]').forEach(function(slider){
    var slides = slider.querySelectorAll('.tc4-home-slide');
    if(slides.length < 2) return;
    var current = 0;
    setInterval(function(){
      slides[current].classList.remove('is-active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('is-active');
    }, 6000);
  });
});
