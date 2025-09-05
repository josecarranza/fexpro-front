$(function () {

    "use strict";

    //This is for the Notification top right


	
	
    var coloresPastel = [
        "#4168ff",
        "#FFC300",
        "#FF006E",
        "#FF00BF",
        "#900C3F",
        "#01c0c8",
        "#FF4136",
        "#FF851B",
        "#FFDC00",
        "#00B159",
        "#0074D9",
        "#FF4136",
        "#7FDBFF",
        "#3D9970",
        "#85144B",
        "#FFDC00",
        "#FF4136",
        "#39CCCC",
        "#B10DC9",
        "#85144B"
      ];


 // Morris donut chart

    let _data = JSON.parse(jQuery("#ecomm-donute-data").val());
    Morris.Donut({

        element: 'morris-donut-chart',

        // data: [{

        //     label: "Orders",
        //     value: jQuery('.totalOrderCount').text(),
        // }, {
        //     label: "FW21",
        //     value: jQuery('.totalPendingOrderCount').text(),
        // }, {
        //     label: "Fexpro POP",
        //     value: jQuery('.totalDeliveredOrderCount').text()
        // }, {
        //     label: "Spring Summer 22",
        //     value: jQuery('.totalpresaleOrderCount').text()
        // }],
        data:_data,
        resize: true,

        colors:coloresPastel

    });

 

});    

    

