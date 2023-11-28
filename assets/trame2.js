import Plotly from 'plotly.js-dist-min'

function calculatePercentage(x, y) {
    return (x*100)/(x*1+y*1);
}

function generateChart(info) {
    return new Promise(function(resolve2, reject2) {

        //Graph 1
        var totalBrut = (info.bonus)*1+(info.grossAnnualSalary)*1;
        var data1 = [{
            values: [info.bonus, info.grossAnnualSalary],
            labels: ['Bonus: '+info.bonus+' €', 'Net: '+info.grossAnnualSalary+' €'],
            hoverinfo: 'label+percent',
            hole: 0.7,
            type: 'pie'
        }];
    
        var layout1 = {
            title: 'Salaire Brut de '+info.firstname+' '+info.name,
            annotations:[{
                font: {size: 10},
                showarrow: false,
                text: 'Total Brut: <br>'+totalBrut+' €',
            }],
            height: 500,
            width: 500,
            hovermode: false,
            showlegend: true,
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
        };
    
        Plotly.newPlot(
            'piechart'+info.matricule,
            data1,
            layout1,
        )


        //Graph 2
        var trace1 = {
            x: [
                calculatePercentage(info.employerSharePension,info.employeeSharePension), 
                calculatePercentage(info.employerShareHealthInsurance,info.employeeShareHealthInsurance), 
                calculatePercentage(info.employerShareMutualInsurance,info.employeeShareMutualInsurance),
            ],
            y: ['Retraite', 'Prévoyance', 'Mutuelle'],
            
            text: [info.employerSharePension+' €', info.employerShareHealthInsurance+' €', info.employerShareMutualInsurance+' €'],
            textposition: 'inside', 
            insidetextanchor: 'middle',
            name: 'Employeur                                                ',
            orientation: 'h',
            marker: {
                color: 'rgb(45, 46, 135)',
            },
            type: 'bar',
        };

        var trace2 = {
            x: [
                calculatePercentage(info.employeeSharePension,info.employerSharePension), 
                calculatePercentage(info.employeeShareHealthInsurance,info.employerShareHealthInsurance), 
                calculatePercentage(info.employeeShareMutualInsurance,info.employerShareMutualInsurance),
            ],
            y: ['Retraite', 'Prévoyance', 'Mutuelle'],
            
            text: [info.employeeSharePension+' €', info.employeeShareHealthInsurance+' €', info.employeeShareMutualInsurance+' €'],
            textposition: 'inside', 
            insidetextanchor: 'middle',
            name: 'Salarié',
            orientation: 'h',
            marker: {
                color: 'rgb(154, 195, 28)',
            },
            type: 'bar',
        };

        var data2 = [trace1, trace2];
    
        var layout2 = {
            title: 'Protection sociale',
            barmode: 'stack',
            xaxis: {
                showgrid: false, 
                showticklabels: false,
                zeroline: false, 
            },
            legend: {
                orientation: 'h',
                x: 0.5,
                y: 1.2,
                xanchor: 'center',
                yanchor: 'top',
                traceorder: 'normal',
            },
            height: 300,
            width: 700,
            hovermode: false,
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
        };
    
        Plotly.newPlot(
            'barchart'+info.matricule,
            data2,
            layout2,
        );
        
        //Graph 3
        var totalProtectionEmployerPercent = (calculatePercentage(info.employerSharePension,info.employeeSharePension)+calculatePercentage(info.employerShareHealthInsurance,info.employeeShareHealthInsurance)+calculatePercentage(info.employerShareMutualInsurance,info.employeeShareMutualInsurance))/3;
        var totalProtectionEmployeePercent = (calculatePercentage(info.employeeSharePension,info.employerSharePension)+calculatePercentage(info.employeeShareHealthInsurance,info.employerShareHealthInsurance)+calculatePercentage(info.employeeShareMutualInsurance,info.employerShareMutualInsurance))/3;

        var totalProtectionEmployer = (info.employerSharePension*1+info.employerShareHealthInsurance*1+info.employerShareMutualInsurance*1);
        var totalProtectionEmployee = (info.employeeSharePension*1+info.employeeShareHealthInsurance*1+info.employeeShareMutualInsurance*1);

        var data3 = [{
            values: [totalProtectionEmployerPercent, totalProtectionEmployeePercent],
            labels: [totalProtectionEmployer+' €<br>Employeur', totalProtectionEmployee+' €<br>Salarié'],
            textinfo: "label+percent",
            hoverinfo: 'label+percent',
            marker: {
                colors: [
                  'rgb(45, 46, 135)',
                  'rgb(154, 195, 28)',
                ],
            },
            type: 'pie',
        }];
    
        var layout3 = {
            height: 250,
            width: 250,
            hovermode: false,
            showlegend: false,
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
        };
    
        Plotly.newPlot(
            'pie2chart'+info.matricule,
            data3,
            layout3,
        )
        
        chartToImage(info).then(
            function() {
                resolve2();
            }
        );
    });
}

function chartToImage (info) {
    return new Promise(function(resolve3, reject3) {
        Plotly.toImage('piechart'+info.matricule, {format: 'png'}).then(function (dataURL1) {
            Plotly.toImage('barchart'+info.matricule, {format: 'png'}).then(function (dataURL2) {
                Plotly.toImage('pie2chart'+info.matricule, {format: 'png'}).then(function (dataURL3) {
                    $.ajax({
                        url: '/infoCollaboratorsRO/donwloadChartImage',
                        method: 'POST',
                        data: {
                            image1: dataURL1,
                            image2: dataURL2,
                            image3: dataURL3,
                            matricule: info.matricule, 
                        },
                        success: function () {
                            resolve3();
                        },
                    });
                });
            });
        });
    });
}

$(function() {
    let promise = Promise.resolve();
    
    $(".information").each(function (e) {
        var info = $(this).data('json');
        //console.log(info);
        $("#generateChartImage"+info.matricule).on( "click", function() {
            promise = promise.then(() => generateChart(info))
        });
        $("#generateAllChartImage").on( "click", function() {
            promise = promise.then(() => generateChart(info))
        });
    });

    $("#btnInfoSup").on( "click", function() {
        var button = $(this);
        button.text(button.text() == "Afficher plus d'information" ? "Afficher moins d'information" : "Afficher plus d'information");
        $(".infoSup").each (function (e) {
            $($(".infoSup")[e]).toggle();
        });
    });
});
