// jQuery('#draggable').draggable({ 
//   appendTo: 'body',
//   helper: 'clone'
// });

// jQuery('#droppable').droppable({
//   activeClass: 'active',
//   hoverClass: 'hover',
//   accept: ":not(.ui-sortable-helper)", // Reject clones generated by sortable
//   drop: function (e, ui) {
//     var $el = jQuery('<div class="drop-item">' + ui.draggable.text() + '<input type="text" /></div>');
//     $el.append(jQuery('<button type="button" class="btn btn-default btn-xs remove">X</button>').click(function () { jQuery(this).parent().detach(); }));
//     jQuery(this).append($el);
//   }
// }).sortable({
//   items: '.drop-item',
//   sort: function() {
//     jQuery( this ).removeClass( "active" );
//   }
// });