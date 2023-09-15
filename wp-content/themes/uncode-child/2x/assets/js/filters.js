jQuery(function ($) {
    var $grid = $('.grid').isotope({
    itemSelector: '.grid-item',
    layoutMode: 'fitRows'
  });
  
  // change is-checked class on buttons
  var $buttonGroup = $('#filters');
  $buttonGroup.on( 'click', 'button', function( event ) {
    $buttonGroup.find('.is-checked').removeClass('is-checked');
    var $button = $( event.currentTarget );
    $button.addClass('is-checked');
    var filterValue = $button.attr('data-filter');
    $grid.isotope({ filter: filterValue });
  });

   
  //****************************
  // Isotope Load more button
  //****************************
  var initShow = 4; //number of images loaded on init & onclick load more button
  var counter = initShow; //counter for load more button
  var iso = $grid.data('isotope'); // get Isotope instance

  loadMore(initShow); //execute function onload

  function loadMore(toShow) {
    $grid.find(".hidden").removeClass("hidden");

    var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function(item) {
      return item.element;
    });
    
    $(hiddenElems).addClass('hidden');
    $grid.isotope('layout');

    //when no more to load, hide show more button
    if (hiddenElems.length == 0) {
      $("#load-more").hide();
    } 
	  else {
      $("#load-more").show();
    };
  }

  //append load more button
  $grid.after('<div class="mt-3 text-center"><button id="load-more" class="load-btn">Show More</button></div>');

  //when load more button clicked
  $("#load-more").click(function() {
    if ($('#filters').data('clicked')) {
      //when filter button clicked, set initial value for counter
      counter = initShow;
      $('#filters').data('clicked', false);
    } else {
      counter = counter;
    };

    counter = counter + initShow;
    loadMore(counter);
  });

  //when filter button clicked
  $('#filters').click(function() {
      $(this).data('clicked', true);
      loadMore(initShow);
  });
});