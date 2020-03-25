var _id=0;
var _set=1;
var cols;


$(function() {
    getData();
});


function getData() {
    
    $.LoadingOverlay("show");
    
    var destinationUrl = "http://127.0.0.1/alcaldia/servicio/setData.php";
    $.ajax({
      type: 'GET',
      url: destinationUrl,
      data: {indicador_id: indicador},
      dataType: 'json', // use json only, not jsonp
      crossDomain: true, // tell browser to allow cross domain.
      success: successResponse,
      error: failureFunction
    });
    
//    setTimeout(
//            function(){
//                getData()
//            }, 60000);
}
  
successResponse = function(data) {
    constructGraf1(data);
    $.LoadingOverlay("hide");
}

failureFunction = function(data) {
 $.LoadingOverlay("hide");
}


function constructGraf1(jSon){
    
//    $('#myChart').remove(); // this is my <canvas> element
//    $('#grafica1').append('<canvas id="myChart"><canvas>');
//    
//    $('#myChart2').remove(); // this is my <canvas> element
//    $('#grafica2').append('<canvas id="myChart2"><canvas>');
//    
//    $('#myChart3').remove(); // this is my <canvas> element
//    $('#grafica3').append('<canvas id="myChart3"><canvas>');
//    
//    $('#myChart4').remove(); // this is my <canvas> element
//    $('#grafica4').append('<canvas id="myChart4"><canvas>');
    
    var _fecha=[];
    var _valordia=[];
    var _limitmax=[];
    var _limitmin=[];
    var _mediavalue=[];
    
    
    for (i in jSon) {
        
        _fecha.push(jSon[i].fecha);
        _limitmax.push(parseFloat(jSon[i].valor_maximo_reporte));
        _limitmin.push(parseFloat(jSon[i].valor_minimo_reporte));
        _mediavalue.push(parseFloat(jSon[i].mediavalue));
        _valordia.push(parseFloat(jSon[i].valor));
    }
    
    //Grafica 1 ==============================================================================================================
    var lineChartData = {
        labels: _fecha,
        datasets:[
            {
                label: 'Limit Superior',
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                fill:false,
                data:_limitmax,
                yAxisID:'y-axis-1'
            },
            {
                label: 'Limite Inferior',
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 1)',
                fill:false,
                data:_limitmin,
                yAxisID:'y-axis-1'
            },
            {
                label: 'Media',
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                fill:false,
                data:_mediavalue,
                yAxisID:'y-axis-1'
            },
            {
                label: 'Valor Dia',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 1)',
                fill:false,
                data:_valordia,
                yAxisID:'y-axis-1'
            }],
    }
    
    
    
    var ctx = document.getElementById('myChart');
    ctx.height = 170;
    var myChart = new Chart(ctx, {
        type: 'line',
        data: lineChartData,
        options: {
            reponsive:true,
            hoverMode:'index',
            stacked:false,
            legend:{
                labels:{
                    fontSize:10
                },
                position: "top",
                align: "start",
            },
            title:{
                display: true,
                text: 'Gestion de Tension [V]'
            },
            scales: {
                yAxes: [
                    {
                    ticks: {
                      fontSize: 9
                    },
                    type:'linear',
                    display:true,
                    position: 'left',
                    id:'y-axis-1'
                   },
                ],
                xAxes:[
                    {
                        ticks: {
                          fontSize: 8
                        },
                    }
                ]
            }
        }
    });
    
    
}


function constructTable(jsonObj) { 
              
    // Getting the all column names 
    if(_set === 1){
       cols = Headers(jsonObj, "#historico");   
       _set = 2;
    }

    // Traversing the JSON data 
    for (var i = 0; i < jsonObj.length; i++) { 
        var row = $('<tr/>');    
        for (var colIndex = 0; colIndex < cols.length; colIndex++) 
        { 
            if(i === (jsonObj.length-1) && colIndex === 0 ){
                _id = jsonObj[i][cols[colIndex]];
            }
            
            var val = jsonObj[i][cols[colIndex]]; 

            // If there is any key, which is matching 
            // with the column name 
            if (val == null) val = "";   
                row.append($('<td/>').html(val)); 
        } 

        // Adding each row to the table 
        $("#historico").append(row); 
    } 
} 


 function Headers(list, selector) { 
    var columns = []; 
    var header = $('<tr/>'); 

    for (var i = 0; i < list.length; i++) { 
        var row = list[i]; 

        for (var k in row) { 
            if ($.inArray(k, columns) == -1) { 
                columns.push(k); 

                // Creating the header 
                header.append($('<th/>').html(k)); 
            } 
        } 
    } 

    // Appending the header to the table 
    $(selector).append(header); 
        return columns; 
}      

