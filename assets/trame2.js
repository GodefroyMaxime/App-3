import Plotly from 'plotly.js-dist-min'

function calculatePercentage (x, y) {
    return (x*100)/(x*1+y*1);
}

function progressBar (iteration, total) {
    return new Promise(function(resolve4, reject4) {
        var progressBar = Math.round($('.progress').length/total*iteration*100*100)/100;
        document.getElementById("progress-bar-chart").style.width = progressBar + "%";
        $("#progress-bar-chart").text(progressBar + "%");
    });
}

async function generateChart (info) {
    return new Promise(async function(resolveGenerate, rejectGenerate) {

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
    
        await Plotly.newPlot(
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
    
        await Plotly.newPlot(
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
    
        await Plotly.newPlot(
            'pie2chart'+info.matricule,
            data3,
            layout3,
        )

        await chartToImage(info)
        resolveGenerate();
    });
}

function chartToImage (info) {
    return new Promise(async function(resolveImage, rejectImage) {
        await Plotly.toImage('piechart'+info.matricule, {format: 'png'}).then(async function (dataURL1) {
            await Plotly.toImage('barchart'+info.matricule, {format: 'png'}).then(async function (dataURL2) {
                await Plotly.toImage('pie2chart'+info.matricule, {format: 'png'}).then(function (dataURL3) {
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
                            reloadGenerateChartImages(this, [info]);
                        }
                    });
                });
            });
        });
    });
}

async function reloadGenerateChartImages (info) {
    const promises = [];
    promises.push(generateChart(info));
    //promises.push($.ajax(BSI));
    
    return Promise.all(promises);
}

function pdf1 (info) {
    return new Promise(async function(resolvePDF1, rejectPDF1) {
        $.ajax({
            url: '/bsi/'+info.matricule,
            method: 'POST',
            data: {
                infos: JSON.stringify(info),
            },
            xhrFields: {
                responseType: 'blob'            
            },
            success: function (/*response, status, xhr*/) {
                // var filename = "test";
                // var disposition = xhr.getResponseHeader('Content-Disposition');

                // if (disposition) {
                //     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                //     var matches = filenameRegex.exec(disposition);
                //     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                // }                 
                // try {
                //     var blob = new Blob([response], { type: 'application/octet-stream' });
                //     if (typeof window.navigator.msSaveBlob !== 'undefined') {
                //         //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."                        window.navigator.msSaveBlob(blob, filename);
                //     } else {
                //         var URL = window.URL || window.webkitURL;
                //         var downloadUrl = URL.createObjectURL(blob);

                //         if (filename) { 
                //             // use HTML5 a[download] attribute to specify filename                            
                //             var a = document.createElement("a");

                //             // safari doesn't support this yet                            
                //             if (typeof a.download === 'undefined') {
                //                 window.location = downloadUrl;
                //             } else {
                //                 a.href = downloadUrl;
                //                 a.download = filename;
                //                 document.body.appendChild(a);
                //                 a.target = "_blank";
                //                 a.click();
                //             }
                //         } else {
                //             window.location = downloadUrl;
                //         }
                //     }   

                // } catch (ex) {
                //     console.log(ex);
                // } 
                resolvePDF1();
            },
            error: async function () {
                await reloadGenerateChartImages(info);
                $.ajax(this);
            }
        });
    });
}

function pdf2 (info) {
    return new Promise(async function(resolvePDF2, rejectPDF2) {
        $.ajax({
            url: '/testTrame/'+info.matricule,
            method: 'POST',
            data: {
                infos: JSON.stringify(info),
            },
            xhrFields: {
                responseType: 'blob'            
            },
            success: function (/*response, status, xhr*/) {
                // var filename = "test";
                // var disposition = xhr.getResponseHeader('Content-Disposition');

                // if (disposition) {
                //     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                //     var matches = filenameRegex.exec(disposition);
                //     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                // }                 
                // try {
                //     var blob = new Blob([response], { type: 'application/octet-stream' });
                //     if (typeof window.navigator.msSaveBlob !== 'undefined') {
                //         //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."                        window.navigator.msSaveBlob(blob, filename);
                //     } else {
                //         var URL = window.URL || window.webkitURL;
                //         var downloadUrl = URL.createObjectURL(blob);

                //         if (filename) { 
                //             // use HTML5 a[download] attribute to specify filename                            
                //             var a = document.createElement("a");

                //             // safari doesn't support this yet                            
                //             if (typeof a.download === 'undefined') {
                //                 window.location = downloadUrl;
                //             } else {
                //                 a.href = downloadUrl;
                //                 a.download = filename;
                //                 document.body.appendChild(a);
                //                 a.target = "_blank";
                //                 a.click();
                //             }
                //         } else {
                //             window.location = downloadUrl;
                //         }
                //     }   

                // } catch (ex) {
                //     console.log(ex);
                // } 
                resolvePDF2();
            },
            error: async function () {
                await reloadGenerateChartImages(info);
                $.ajax(this);
            }
        });
    });
}

async function processCurrentPage (btnId, nextPage, allMatricule, progressBarIndex, totalCollaborators) {
    $("#blockingOverlay").show();
    $("body").css("cursor", "wait");

    var promises = [];
    var info = $('.table').data('collaboratorsjson');

    for (let index = 0; index < info.length; index++) {
        progressBar(progressBarIndex++, totalCollaborators);
        const element = info[index];
        allMatricule.push(element.matricule);
        if (btnId == "generateAllChartImage") {
            promises.push(await generateChart(element));
        } else if (btnId == "generateAllBSI") {
            promises.push(await pdf1(element));
        }
    }

    return Promise.all(promises).then(function() {
        $("#blockingOverlay").hide();
        $("body").css("cursor", "auto");
        nextPage++;
        localStorage.setItem('progressBarIndex', progressBarIndex);
        localStorage.setItem('allMatricule', JSON.stringify(allMatricule));
        localStorage.setItem('nextPage', nextPage);
        localStorage.setItem('btnId', btnId);
    })
}

function processPages(btnId, nextPage, progressBarIndex, allMatricule) {

    let totalCollaborators = $("#totalCollaborators").val()*1;
    let totalPages = $("#nbrPage").val()*1;

    if (nextPage <= totalPages) {
        return new Promise(function(resolveProcessPage, rejectProcessPage) {
            processCurrentPage(btnId, nextPage, allMatricule, progressBarIndex, totalCollaborators)
            .then(() => {
                let url = new URL(location.href);
                url.searchParams.set('page', nextPage);
                window.location.href = url.href;
                resolveProcessPage();
            })
            .catch((error) => {
                rejectProcessPage(error);
            });
        });
    
    } else {
        return new Promise(function(resolveProcessPage, rejectProcessPage) {
            processCurrentPage(btnId, nextPage, allMatricule, progressBarIndex, totalCollaborators)
            .then(() => {
                localStorage.removeItem('progressBarIndex');
                localStorage.removeItem('allMatricule');
                localStorage.removeItem('nextPage');
                localStorage.removeItem('btnId');

                $.ajax({
                    url: '/downloadZIP',
                    method: 'POST',
                    data:  {
                        allMatricule: JSON.stringify(allMatricule),
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
                    }
                });

                resolveProcessPage();
                
                $(".progress").hide()
            })
            .catch((error) => {
                rejectProcessPage(error);
            });
        });
    }
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

    let progressBarIndex = localStorage.getItem('progressBarIndex');
    let btnId = localStorage.getItem('btnId');
    let nextPage = localStorage.getItem('nextPage');
    let allMatricule = JSON.parse(localStorage.getItem('allMatricule'));
    if (nextPage && btnId && allMatricule) {
        $(".progress").show()
        processPages(btnId, nextPage, progressBarIndex, allMatricule);
    }

    $(".btnScript").on("click", function() {
        $(".progress").show()
        var allMatricule = [];
        let nextPage = 2;
        let progressBarIndex = 1;
        processPages($(this).attr('id'), nextPage, progressBarIndex, allMatricule);
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
