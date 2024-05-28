'use strict';

/**
 * navbar toggle
 */

const overlay = document.querySelector("[data-overlay]");
const navOpenBtn = document.querySelector("[data-nav-open-btn]");
const navbar = document.querySelector("[data-navbar]");
const navCloseBtn = document.querySelector("[data-nav-close-btn]");
const navLinks = document.querySelectorAll("[data-nav-link]");

const navElemArr = [navOpenBtn, navCloseBtn, overlay];

const navToggleEvent = function (elem) {
  for (let i = 0; i < elem.length; i++) {
    elem[i].addEventListener("click", function () {
      navbar.classList.toggle("active");
      overlay.classList.toggle("active");
    });
  }
}

navToggleEvent(navElemArr);
navToggleEvent(navLinks);



/**
 * header sticky & go to top
 */

const header = document.querySelector("[data-header]");
const goTopBtn = document.querySelector("[data-go-top]");

window.addEventListener("scroll", function () {

  if (window.scrollY >= 200) {
    header.classList.add("active");
    goTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    goTopBtn.classList.remove("active");
  }

});


document.addEventListener('DOMContentLoaded', function() {
  var logoImage = document.querySelector('.logo-lm img');
  var originalLogo = logoImage.src; 
  var darkLogo = './assets/images/logoLM-dark.png'; 

  window.addEventListener('scroll', function() {

      if (window.scrollY > -1) {
          logoImage.src = darkLogo;
      } else {
          logoImage.src = darkLogo;
      }
  });
});

function toggleMode() {
  const body = document.body;
  const modeSwitch = document.getElementById('mode-switch');
  const logoImage = document.querySelector('.logo-lm img');
  const originalLogo = logoImage.getAttribute('data-original-src');
  const darkLogo = './assets/images/logoLM-dark.png';

  if (modeSwitch.checked) {
    body.classList.add('dark-mode');
    logoImage.src = darkLogo;
    localStorage.setItem('darkMode', 'true'); 
  } else {
    body.classList.remove('dark-mode');
    logoImage.src = originalLogo;
    localStorage.setItem('darkMode', 'false');
  }
}


function initializeToggle() {
  const darkMode = localStorage.getItem('darkMode');
  const modeSwitch = document.getElementById('mode-switch');
  if (darkMode === 'true') {
    modeSwitch.checked = true;
    toggleMode();
  } else {
    modeSwitch.checked = false;
  }
}


window.onload = function() {
  initializeToggle();
};

function toggleDropdown() {
  const dropdownMenu = document.getElementById('dropdownMenu');
  dropdownMenu.classList.toggle('show');
}

$(document).ready(function() {
  $('.navbar-link.dropdown-toggle').click(function(event) {
      event.preventDefault();
      console.log("Dropdown should toggle.");
      $(this).next('.dropdown-menu').toggle(); 
  });
});

