// Initialize Semantic UI components
$('.ui.dropdown').dropdown();

// Make all elements with `href` attribute clickable
$('[href]').not('a').click(function () {
  location.href = $(this).attr('href');
});
