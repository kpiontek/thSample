$(function() {
  $('.arrowUp, .arrowDown').click(function() {
    var theme_id = $(this).parents('ul').attr('id').replace('theme','');
    var jqxhr = $.ajax("/themes/updateVotes/"+theme_id+"/"+$(this).attr('class'))
    .done(function(response) {
      //alert( "success! "+response );
    })
    .fail(function() {
      //alert( "error! "+response );
    });
    
    var current = +($(this).parents('li').find('.voteVal').text());
    
    if ($(this).attr('class') == 'arrowUp')
      var change = 1;
    else
      var change = -1;
      
    var new_val = current+change;
    $(this).parents('li').find('.voteVal').text(new_val);
  });
});
