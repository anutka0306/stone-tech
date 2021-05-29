(function ($) {
    'use strict';
    $.fn.tableanarchy = function ( options ) {
        var tableanarchy = this,
            settings = $.extend({
                labelClass: "",      // Class of row labels.
                containerClass: ""   // Optional class to give the new container.
            }, options),
            aTableSelectors, currentSelector, tableRows, tableCells,
            className, contents, $newCont1, $newCont2, $newCont3;

        //$("#msg").text(tableanarchy.selector);

        //Create a main container to hold the moved table cell elements.
        $newCont1 = $('<div />');
        if ( settings.containerClass ) {
            $newCont1.addClass(settings.containerClass);
        }

        //Place the main container just above the existing table.
        tableanarchy.before($newCont1);

        //Get all table rows from the table, saving them in a JS object.
        tableRows = $(tableanarchy.selector + ' tr');

        //Loop through each item (table row) in the JS object.
        $.each( tableRows, function( count, item ) {
            $newCont2 = $('<div />');

            //Copy any table row's attributes to the current item
            //$(item).each(function() {
            $.each( this.attributes, function( count, attrib ){
                var name = attrib.name;
                var value = attrib.value;
                $newCont2.attr(name, value);
            });
            //});

            //Detach all table cells from the current item, saving them in a JS object.
            tableCells = $(item).children().detach();

            //Loop through each item in the JS object.
            $.each( tableCells, function( count, item ) {
                className = $(item).attr('class');
                contents = $(item).contents();
                if ( className === settings.labelClass ) {
                    //Create a new container with the same class as the existing item.
                    $newCont3 = $('<label />');
                } else {
                    $newCont3 = $('<div />');
                }

                //Add the contents of the item to the new container.
                $newCont3.append(contents).addClass(className);

                /*Add class to container to indicate which table column it originally came
                  from. This will provide an additional method for styling the content.*/
                $newCont3.addClass( 'col' + (count + 1).toString() );

                //Add the new container to the main container.
                $newCont2.append($newCont3);
            });

            //Add a break at the end of the container so that the items will wrap correctly.
            //$newCont2.append('<br />'); // I'm not sure why I put this here. :(
            $newCont1.append($newCont2);
        });

        //Remove the now empty original table.
        tableanarchy.remove();
    };
}(jQuery));