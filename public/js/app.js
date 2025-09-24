// Basic JS placeholder (no build tools)
document.addEventListener('DOMContentLoaded', function(){
  // Example: smooth scroll to top on nav brand click
  var brand = document.querySelector('header a[href="/"]');
  if(brand){
    brand.addEventListener('click', function(){ window.scrollTo({top:0, behavior:'smooth'}); });
  }
});






