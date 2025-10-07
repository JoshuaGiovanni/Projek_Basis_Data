// Basic JS placeholder (no build tools)
document.addEventListener('DOMContentLoaded', function(){
  // Example: smooth scroll to top on nav brand click
  var brand = document.querySelector('header a[href="/"]');
  if(brand){
    brand.addEventListener('click', function(){ window.scrollTo({top:0, behavior:'smooth'}); });
  }

  // Sidebar toggle
  var sidebar = document.getElementById('sidebar');
  var sidebarBackdrop = document.getElementById('sidebarBackdrop');
  var openBtn = document.getElementById('sidebarToggle');
  var closeBtn = document.getElementById('sidebarClose');

  function openSidebar(){
    if(!sidebar) return;
    sidebar.classList.remove('-translate-x-full');
    if(sidebarBackdrop){ sidebarBackdrop.classList.remove('hidden'); }
  }
  function closeSidebar(){
    if(!sidebar) return;
    sidebar.classList.add('-translate-x-full');
    if(sidebarBackdrop){ sidebarBackdrop.classList.add('hidden'); }
  }

  if(openBtn){ openBtn.addEventListener('click', openSidebar); }
  if(closeBtn){ closeBtn.addEventListener('click', closeSidebar); }
  if(sidebarBackdrop){ sidebarBackdrop.addEventListener('click', closeSidebar); }
});














