<?php $epcl_theme = epcl_get_theme_options(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
	
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
	<meta name="apple-mobile-web-app-title" content="888Starz" />
	<link rel="manifest" href="/site.webmanifest" />
    <?php if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ): ?>
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
    <?php endif; ?>

    <?php wp_head(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var showMoreButton = document.getElementById('show-more');
    var tocItems = document.querySelectorAll('.custom-toc .toc-item.toc-hidden');

    if (showMoreButton) {
        showMoreButton.addEventListener('click', function() {
            // Показать скрытые элементы
            tocItems.forEach(function(item) {
                item.classList.remove('toc-hidden');
            });

            // Скрыть кнопку
            showMoreButton.style.display = 'none';
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
  // Найти все таблицы без класса
  const tables = document.querySelectorAll("table:not([class])");
  
  tables.forEach((table) => {
    // Создать обертку
    const wrapper = document.createElement("div");
    wrapper.className = "table-wrapper";
    
    // Вставить обертку перед таблицей
    table.parentNode.insertBefore(wrapper, table);
    // Поместить таблицу внутрь обертки
    wrapper.appendChild(table);
  });
});
	
function copyPromoCode(event) {
  var notification = document.getElementById("copyNotification");
  
  // Check if the notification is not visible
  if (notification.style.display !== "block") {
    var promoCode = document.getElementById("promoCode").innerText;
    var tempInput = document.createElement("input");
    document.body.appendChild(tempInput);
    tempInput.setAttribute("value", promoCode);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    notification.style.display = "block";

    setTimeout(function() {
      notification.style.display = "none";
    }, 2000);
  }
}
  </script>
	
<script>
var initialHours = 0;
var initialMinutes = 3;
var initialSeconds = 0;

function formatTime(time) {
  return time < 10 ? "0" + time : time;
}

function getRemainingTime(endTime) {
  var currentTime = Date.now();
  var timeRemaining = Math.floor((endTime - currentTime) / 1000);
  return timeRemaining;
}

function initializeTimer(hours, minutes, seconds) {
  // Проверяем, есть ли сохраненное время окончания в localStorage
  var savedEndTime = localStorage.getItem('endTime');
  var timeRemaining;
  if (savedEndTime) {
    timeRemaining = getRemainingTime(savedEndTime);
    if (timeRemaining <= 0) {
      // Если сохраненное время уже истекло, очищаем localStorage и перезапускаем таймер с начальными параметрами
      localStorage.removeItem('endTime');
      var newEndTime = Date.now() + (hours * 3600 + minutes * 60 + seconds) * 1000;
      localStorage.setItem('endTime', newEndTime);
      timeRemaining = getRemainingTime(newEndTime);
    }
  } else {
    // Если в localStorage нет сохраненного времени, инициализируем новое время окончания
    var endTime = Date.now() + (hours * 3600 + minutes * 60 + seconds) * 1000;
    localStorage.setItem('endTime', endTime);
    timeRemaining = getRemainingTime(endTime);
  }

  startTimer(timeRemaining);
}
function startTimer(timeRemaining) {
    var timerInterval = setInterval(function() {
        // Проверяем наличие необходимых элементов
        var hoursElement = document.querySelector(".timer__hours");
        var minutesElement = document.querySelector(".timer__minutes");
        var secondsElement = document.querySelector(".timer__seconds");
        
        if (!hoursElement || !minutesElement || !secondsElement) {
            clearInterval(timerInterval);
            return;
        }
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            hoursElement.innerText = "00";
            minutesElement.innerText = "00";
            secondsElement.innerText = "00";
            localStorage.removeItem('endTime');
            
            // Очищаем, чтобы можно было перезапустить
            if (typeof initializeTimer === 'function') {
                initializeTimer(initialHours, initialMinutes, initialSeconds);
            } else {
                console.error('Функция initializeTimer не определена.');
            }
            // Автоматический перезапуск с начальными параметрами
            return;
        }

        var hours = Math.floor(timeRemaining / 3600);
        var minutes = Math.floor((timeRemaining % 3600) / 60);
        var seconds = Math.floor(timeRemaining % 60);

        hoursElement.innerText = formatTime(hours);
        minutesElement.innerText = formatTime(minutes);
        secondsElement.innerText = formatTime(seconds);

        timeRemaining--;
    }, 1000);
}


document.addEventListener("DOMContentLoaded", function() {
  initializeTimer(initialHours, initialMinutes, initialSeconds);
});
</script>
  <!-- jQuery подключается через wp_enqueue_script в wp_head(), не дублируем -->
  <script>
    jQuery(document).ready(function ($) {
      $('.faq-question').click(function () {
        var isOpen = $(this).hasClass('open');
  
        // Toggle the open/close state for the clicked question
        $(this).toggleClass('open');
        $(this).find('.checkmark').toggleClass('rotate');
        $(this).next('.faq-answer').slideToggle();
  
        // Close other open answers if the clicked question was closed
        if (!isOpen) {
          $('.faq-question.open').not(this).removeClass('open');
          $('.faq-answer').not($(this).next()).slideUp();
          $('.checkmark').not($(this).find('.checkmark')).removeClass('rotate');
        }
      });
	  $('.ez-question').click(function () {
			var isOpen = $(this).hasClass('open');

			// Toggle the open/close state for the clicked question
			$(this).toggleClass('open');
			$(this).find('.checkmark').toggleClass('rotate');
			$(this).next('.ez-answer').slideToggle();
			// Close other open answers if the clicked question was closed
			if (!isOpen) {
				$('.ez-question.open').not(this).removeClass('open');
				$('.ez-answer').not($(this).next()).slideUp();
				$('.checkmark').not($(this).find('.checkmark')).removeClass('rotate');
			}
		});
    });

  
	  


    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.link-game-888');
        const toggleBtn = document.getElementById('toggle-btn');
        let isExpanded = false;
        
        // Изначально показываем только 8 элементов
        const visibleLinks = 8;
        links.forEach((link, index) => {
            if (index >= visibleLinks) link.style.display = 'none';
        });

        // Обработчик события для кнопки
        toggleBtn.addEventListener('click', () => {
            isExpanded = !isExpanded;
            if (isExpanded) {
                // Показываем все элементы
                links.forEach(link => link.style.display = 'flex');
				links.forEach(link => link.style.direction = 'column');
                toggleBtn.innerText = 'Hide';
            } else {
                // Скрываем элементы за пределами первых 8
                links.forEach((link, index) => {
                    if (index >= visibleLinks) link.style.display = 'none';
                });
                toggleBtn.innerText = 'Show more';
            }
        });
    });
  </script>	
</head>
<body <?php body_class(); ?>>  
    <?php wp_body_open(); ?>

    <?php get_template_part('partials/header/navigation-side'); ?>

    <!-- start: #wrapper -->
    <div id="wrapper">
		<?php get_template_part('partials/header'); ?>
