const fs = require('fs');
const path = require('path');

const htmlFiles = [
  'public/index.html',
  'public/about/index.html',
  'public/registration/index.html',
  'public/dispute-resolution/index.html',
  'public/apk/index.html',
  'public/promo-code/index.html',
  'public/terms/index.html',
  'public/self-exclusion/index.html',
  'public/accounts-withdrawals-and-bonuses/index.html',
  'public/fairness-rng-testing-methods/index.html',
  'public/contacts/index.html',
  'public/responsible/index.html',
  'public/privacy-policy/index.html',
  'public/cookies/index.html'
];

const oldOpenMenuCSS = `\t#header .open-menu
\t{
\t\tposition:relative;
\t\tgap:5px;
\t\tright:0;
\t\tleft:0;
\t\theight:67px;
\t\tbottom:0;
\t\tmargin:0;
\t\tdisplay:grid;
\t\tjustify-items:center;
\t\talign-items:end;
\t\talign-content:center;
\t\tcursor:pointer;
\t\tuser-select:none;
\t}`;

const newOpenMenuCSS = `\t#header .open-menu,
\t.mobile-bottom-navigator-header .open-menu
\t{
\t\tposition:relative;
\t\tgap:5px;
\t\tright:0;
\t\tleft:0;
\t\theight:67px;
\t\tbottom:0;
\t\tmargin:0;
\t\tdisplay:grid;
\t\tjustify-items:center;
\t\talign-items:end;
\t\talign-content:center;
\t\tcursor:pointer;
\t\tuser-select:none;
\t\t-webkit-tap-highlight-color:transparent;
\t\tpointer-events:auto;
\t\tz-index:10;
\t}`;

const oldMenuCSS = `\t/* Мобильное меню - скрыто по умолчанию */
\tnav.mobile.side-nav {
\t\tposition: fixed;
\t\ttop: 0;
\t\tleft: 0;
\t\twidth: 100%;
\t\theight: 100vh;
\t\tbackground: #2b2b2b;
\t\tz-index: 9999;
\t\toverflow-y: auto;
\t\ttransform: translateX(-100%);
\t\ttransition: transform 0.3s ease-in-out;
\t\tdisplay: block !important;
\t}
\t
\t/* Мобильное меню - открыто */
\tnav.mobile.side-nav.active {
\t\ttransform: translateX(0);
\t}`;

const newMenuCSS = `\t/* Мобильное меню - скрыто по умолчанию */
\tnav.mobile.side-nav {
\t\tposition: fixed;
\t\ttop: 0;
\t\tleft: 0;
\t\twidth: 100%;
\t\theight: 100vh;
\t\tbackground: #2b2b2b;
\t\tz-index: 99999;
\t\toverflow-y: auto;
\t\ttransform: translateX(-100%);
\t\ttransition: transform 0.3s ease-in-out;
\t\tdisplay: block !important;
\t\tvisibility: hidden;
\t\topacity: 0;
\t}
\t
\t/* Мобильное меню - открыто */
\tnav.mobile.side-nav.active {
\t\ttransform: translateX(0);
\t\tvisibility: visible;
\t\topacity: 1;
\t}`;

const oldMenuJS = `\t\t\t// Обработчик открытия мобильного меню
\t\t\tvar $openMenuBtn = jQuery('.mobile-bottom-navigator-header .open-menu');
\t\t\tvar $mobileMenu = jQuery('nav.mobile.side-nav');
\t\t\tvar $closeBtn = jQuery('nav.mobile.side-nav .close');
\t\t\t
\t\t\tif ($openMenuBtn.length && $mobileMenu.length) {
\t\t\t\t// Открытие меню
\t\t\t\t$openMenuBtn.on('click', function(e) {
\t\t\t\t\te.preventDefault();
\t\t\t\t\te.stopPropagation();
\t\t\t\t\t$mobileMenu.addClass('active');
\t\t\t\t\tjQuery('body').addClass('menu-open');
\t\t\t});
\t\t\t
\t\t\t\t// Закрытие меню
\t\t\t\t$closeBtn.on('click', function(e) {
\t\t\t\t\te.preventDefault();
\t\t\t\t\te.stopPropagation();
\t\t\t\t\t$mobileMenu.removeClass('active');
\t\t\t\t\tjQuery('body').removeClass('menu-open');
\t\t\t});
\t\t\t
\t\t\t\t// Закрытие меню при клике вне его
\t\t\t\tjQuery(document).on('click', function(e) {
\t\t\t\t\tif ($mobileMenu.hasClass('active') && 
\t\t\t\t\t\t!jQuery(e.target).closest('nav.mobile.side-nav').length &&
\t\t\t\t\t\t!jQuery(e.target).closest('.open-menu').length) {
\t\t\t\t\t\t$mobileMenu.removeClass('active');
\t\t\t\t\t\tjQuery('body').removeClass('menu-open');
\t\t\t\t}
\t\t\t\t});
\t\t\t}`;

const newMenuJS = `\t\t\t// Обработчик открытия мобильного меню
\t\t\tvar $openMenuBtn = jQuery('.mobile-bottom-navigator-header .open-menu');
\t\t\tvar $mobileMenu = jQuery('nav.mobile.side-nav');
\t\t\tvar $closeBtn = jQuery('nav.mobile.side-nav .close');
\t\t\t
\t\t\t// Также пробуем найти кнопку через другие селекторы
\t\t\tif (!$openMenuBtn.length) {
\t\t\t\t$openMenuBtn = jQuery('.open-menu');
\t\t\t}
\t\t\t
\t\t\tif ($openMenuBtn.length && $mobileMenu.length) {
\t\t\t\t// Открытие меню
\t\t\t\t$openMenuBtn.off('click.mobileMenu').on('click.mobileMenu', function(e) {
\t\t\t\t\te.preventDefault();
\t\t\t\t\te.stopPropagation();
\t\t\t\t\te.stopImmediatePropagation();
\t\t\t\t\t$mobileMenu.addClass('active');
\t\t\t\t\tjQuery('body').addClass('menu-open').css('overflow', 'hidden');
\t\t\t});
\t\t\t
\t\t\t\t// Закрытие меню
\t\t\t\tif ($closeBtn.length) {
\t\t\t\t\t$closeBtn.off('click.mobileMenu').on('click.mobileMenu', function(e) {
\t\t\t\t\t\te.preventDefault();
\t\t\t\t\t\te.stopPropagation();
\t\t\t\t\t\te.stopImmediatePropagation();
\t\t\t\t\t\t$mobileMenu.removeClass('active');
\t\t\t\t\t\tjQuery('body').removeClass('menu-open').css('overflow', '');
\t\t\t\t});
\t\t\t}
\t\t\t
\t\t\t\t// Закрытие меню при клике вне его
\t\t\t\tjQuery(document).off('click.mobileMenu').on('click.mobileMenu', function(e) {
\t\t\t\t\tif ($mobileMenu.hasClass('active') && 
\t\t\t\t\t\t!jQuery(e.target).closest('nav.mobile.side-nav').length &&
\t\t\t\t\t\t!jQuery(e.target).closest('.open-menu').length &&
\t\t\t\t\t\t!jQuery(e.target).closest('.mobile-bottom-navigator-header').length) {
\t\t\t\t\t\t$mobileMenu.removeClass('active');
\t\t\t\t\t\tjQuery('body').removeClass('menu-open').css('overflow', '');
\t\t\t\t}
\t\t\t\t});
\t\t\t}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Обновить CSS для кнопки меню
  if (content.includes('#header .open-menu') && !content.includes('.mobile-bottom-navigator-header .open-menu')) {
    content = content.replace(oldOpenMenuCSS, newOpenMenuCSS);
    modified = true;
  }
  
  // Обновить CSS для меню
  if (content.includes('nav.mobile.side-nav {') && !content.includes('visibility: hidden')) {
    content = content.replace(oldMenuCSS, newMenuCSS);
    modified = true;
  }
  
  // Обновить JavaScript обработчики
  if (content.includes('var $openMenuBtn = jQuery') && !content.includes('stopImmediatePropagation')) {
    content = content.replace(oldMenuJS, newMenuJS);
    modified = true;
  }
  
  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`Updated: ${file}`);
  } else {
    console.log(`No changes needed: ${file}`);
  }
});

console.log('Done!');
