$('#product_tabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

$('.carousel').carousel();
