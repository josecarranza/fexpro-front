$(function () {

    "use strict";

    //This is for the Notification top right


	
	



 // Morris donut chart


    Morris.Donut({

        element: 'morris-donut-chart',

        data: [{

            label: "Orders",
            value: jQuery('.totalOrderCount').text(),
        }, {
            label: "FW21",
            value: jQuery('.totalPendingOrderCount').text(),
        }, {
            label: "Fexpro POP",
            value: jQuery('.totalDeliveredOrderCount').text()
        }, {
            label: "Spring Summer 22",
            value: jQuery('.totalpresaleOrderCount').text()
        }],

        resize: true,

        colors:['#fb9678', '#01c0c8', '#4F5467', '#4168ff']

    });

 

});    

    

