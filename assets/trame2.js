import Plotly from 'plotly.js-dist-min'

function calculatePercentage (x, y) {
    return (x*100)/(x*1+y*1);
}

function generateChart (info) {
    return new Promise(function(resolveGenerate, rejectGenerate) {

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
            
            text: [calculatePercentage(info.employerSharePension,info.employeeSharePension).toFixed(2)+' %', calculatePercentage(info.employerShareHealthInsurance,info.employeeShareHealthInsurance).toFixed(2)+' %', calculatePercentage(info.employerShareMutualInsurance,info.employeeShareMutualInsurance).toFixed(2)+' %'],
            textposition: 'inside', 
            insidetextanchor: 'middle',
            name: 'Employeur',
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
            
            text: [calculatePercentage(info.employeeSharePension,info.employerSharePension).toFixed(2)+' %', calculatePercentage(info.employeeShareHealthInsurance,info.employerShareHealthInsurance).toFixed(2)+' %', calculatePercentage(info.employeeShareMutualInsurance,info.employerShareMutualInsurance).toFixed(2)+' %'],
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
        var totalProtectionEmployer = (info.employerSharePension*1+info.employerShareHealthInsurance*1+info.employerShareMutualInsurance*1);
        var totalProtectionEmployee = (info.employeeSharePension*1+info.employeeShareHealthInsurance*1+info.employeeShareMutualInsurance*1);

        var data3 = [{
            values: [totalProtectionEmployer, totalProtectionEmployee],
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

        chartToImage(info).then(function() {
            resolveGenerate();
        });
    });
}

function chartToImage (info) {
    return new Promise(function(resolveImage, rejectImage) {
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
                            resolveImage();
                        },
                        error: function () {
                            location.reload();
                        }
                    });
                });
            });
        });
    });
}

async function reloadGenerateChartImages (BSI, infos) {
    const promises = infos.map(info => generateChart(info));
    
    await Promise.all(promises);
    return await $.ajax(BSI);
}

function pdf1 (info) {
    $.ajax({
        url: '/bsi/'+info.matricule,
        method: 'POST',
        data: {
            infos: JSON.stringify(info),
        },
        xhrFields: {
            responseType: 'blob'            
        },
        success: function (response, status, xhr) {
            var filename = "test";
            var disposition = xhr.getResponseHeader('Content-Disposition');

            if (disposition) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }                 
            try {
                var blob = new Blob([response], { type: 'application/octet-stream' });
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."                        window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) { 
                        // use HTML5 a[download] attribute to specify filename                            
                        var a = document.createElement("a");

                        // safari doesn't support this yet                            
                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.target = "_blank";
                            a.click();
                        }
                    } else {
                        window.location = downloadUrl;
                    }
                }   

            } catch (ex) {
                console.log(ex);
            } 
        },
        error: function () {
            reloadGenerateChartImages(this, [info]);
        }
    });
}

function pdf2 (info) {
    $.ajax({
        url: '/testTrame/'+info.matricule,
        method: 'POST',
        data: {
            infos: JSON.stringify(info),
        },
        xhrFields: {
            responseType: 'blob'            
        },
        success: function (response, status, xhr) {
            var filename = "test";
            var disposition = xhr.getResponseHeader('Content-Disposition');

            if (disposition) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }                 
            try {
                var blob = new Blob([response], { type: 'application/octet-stream' });
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."                        window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) { 
                        // use HTML5 a[download] attribute to specify filename                            
                        var a = document.createElement("a");

                        // safari doesn't support this yet                            
                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.target = "_blank";
                            a.click();
                        }
                    } else {
                        window.location = downloadUrl;
                    }
                }   

            } catch (ex) {
                console.log(ex);
            } 
        },
        error: function () {
            reloadGenerateChartImages(this, [info]);
        }
    });
}

function processCurrentPage (btnId) {
    var info = $('.table').data('collaboratorsjson');
    
    $.each(info, function () {
        if (btnId == "generateAllChartImage") {
            generateChart(this);
        } else if (btnId == "generateAllBSI") {
            pdf1(this);
        }
    });
}

$(function() {
    $(".information").each(function () {
        var info = $(this).data('collaboratorjson');
        $("#generateChartImage"+info.matricule).on( "click", function () {
            generateChart(info);
        });
           
        $("#generateBSI"+info.matricule).on( "click", function () {
            pdf1(info);
        });

        $("#testTrame"+info.matricule).on( "click", function () {
            pdf2(info);            
        });        
    });

    $(".btnScript").on("click", function() {
        processCurrentPage($(this).attr('id'));
    });




    // Bloqué vu que pas besoin mais à garder
    // $("#generateOneBSI").on( "click", function () {
    //     var infos = $(".table").data('collaboratorsjson');
    //     $.ajax({
    //         url: '/AllBSI',
    //         method: 'POST',
    //         data: {
    //             infos: JSON.stringify(infos),
    //         },
    //         xhrFields: {
    //             responseType: 'blob'            
    //         },
    //         success: function (response, status, xhr) {
    //             var filename = "test";
    //             var disposition = xhr.getResponseHeader('Content-Disposition');

    //             if (disposition) {
    //                 var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
    //                 var matches = filenameRegex.exec(disposition);
    //                 if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
    //             }                 
    //             try {
    //                 var blob = new Blob([response], { type: 'application/octet-stream' });
    //                 if (typeof window.navigator.msSaveBlob !== 'undefined') {
    //                     //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."                        window.navigator.msSaveBlob(blob, filename);
    //                 } else {
    //                     var URL = window.URL || window.webkitURL;
    //                     var downloadUrl = URL.createObjectURL(blob);

    //                     if (filename) { 
    //                         // use HTML5 a[download] attribute to specify filename                            
    //                         var a = document.createElement("a");

    //                         // safari doesn't support this yet                            
    //                         if (typeof a.download === 'undefined') {
    //                             window.location = downloadUrl;
    //                         } else {
    //                             a.href = downloadUrl;
    //                             a.download = filename;
    //                             document.body.appendChild(a);
    //                             a.target = "_blank";
    //                             a.click();
    //                         }
    //                     } else {
    //                         window.location = downloadUrl;
    //                     }
    //                 }   

    //             } catch (ex) {
    //                 console.log(ex);
    //             } 
    //         },
    //         error: function () {
    //             reloadGenerateChartImages(this, infos);
    //         }
    //     });
    // });

    $("#btnInfoSup").on( "click", function () {
        var button = $(this);
        button.text(button.text() == "Afficher plus d'information" ? "Afficher moins d'information" : "Afficher plus d'information");
        $(".infoSup").each (function (e) {
            $($(".infoSup")[e]).toggle();
        });
    });
});
