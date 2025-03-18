jQuery(document).ready(function(jQuery){

    jQuery("$('div[id^='Ajx_']").click(function(e){

        /*--------------------------------------------------------------------*/

            alert('here');
            var id  = jQuery(this).attr('id').split('Ajx_');       /* check if id is correct */

            if (id[1] == "")

                {

             	exit() ;

                }

        /*--------------------------------------------------------------------   create path */

        var i = 0;

		var pathname = window.location.protocol +"//"+ window.location.hostname + "/system/";

		var URL = pathname   + "index.php?Data/Postdata";

        /*--------------------------------------------------------------------*/
		var myObject = new Object();

		myObject.Pr_id =  jQuery(this).attr('id');

		myObject.url   =  URL ;

		var ser = JSON.stringify(myObject);

		var xhr = jQuery.ajax(

            {

                type: "Post",
      			url: URL ,
        		data: {
				arr: ser

	    		},

			datatype: 'json',

	
			success: function( response ) {

					window.top.jQuery('#qn').html(response);
			}

		});

	});
  	});