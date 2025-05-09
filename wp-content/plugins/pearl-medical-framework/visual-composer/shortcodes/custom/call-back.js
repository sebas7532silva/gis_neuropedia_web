

$ = jQuery;

toggle_remove_buttons();

function add_cloned_fields( $input )
{
    var $clone_last = $input.find( '.input_field:last' ),
        $clone = $clone_last.clone(),
        $input, name;
    $clone.insertAfter( $clone_last );
    $input = $clone.find( '.fajar_clone' );
    // Reset value
    $input.val( '' );


    // Get the field name, and increment
    name = $input.attr( 'name' ).replace( /\[(\d+)\]/, function( match, p1 )
    {
        return '[' + ( parseInt( p1 ) + 1 ) + ']';
    } );

    // Update the "name" attribute
    $input.attr( 'name', name );

    // Toggle remove buttons
    toggle_remove_buttons( $input );

    //Trigger custom clone event
    $input.trigger( 'clone' );
}

// Add more clones
$( '.add_clone_one' ).on( 'click', function( e )
{
    e.stopPropagation();
    var $input = $( this ).parents( '.clone_one_block' );
    add_cloned_fields( $input );
    toggle_remove_buttons( $input );

} );

// Remove clones
$( '.clone_one_block' ).on( 'click', '.remove_clone_one', function()
{

    var $this = $( this ),
        $input = $this.parents( '.clone_one_block' );

    // Remove clone only if there're 2 or more of them
    if ( $input.find( '.input_field' ).length <= 1 )
        return false;

    $this.parent().remove();

    // Toggle remove buttons
    toggle_remove_buttons( $input );

    return false;
} );

/**
 * Hide remove buttons when there's only 1 of them
 *
 * @param $el jQuery element. If not supplied, the function will applies for all fields
 *
 * @return void
 */
function toggle_remove_buttons( $el )
{
    var $button;
    if ( !$el )
        $el = $( '.clone_one_block' );
    $el.each( function()
    {
        $button = $( this ).find( '.remove_clone_one' );
        $button.length < 2 ? $button.hide() : $button.show();
    } );
}

var count = 0;

$('.vc_ui-button-action').on('click',function(e){

    count++;
if(count == 1){
    var wrap = $('.clone_one_block');

    wrap.each(function(e){
        var $this = $(this);
        var values = '';
        var length = $(this).find('.fajar_clone').length;
        var selector  = $(this).find('.fajar_clone');
        if(length > 1){
            selector.each(function(e){
                values += $(this).val()+'*';
            });
            var toAdd = $(this).children('.input_field').children('.fajar_clone');
            if($(this).hasClass('fajar_textarea')){
                toAdd.attr('value',values);
            }else{
                toAdd.attr('value',values);
            }


        }
    });
}
    console.log(count);

});
