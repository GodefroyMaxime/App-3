import Plotly from 'plotly.js-dist-min'

function generateChart(nbChart, total) {
    return new Promise(function(resolve2, reject2) {
        var data = [{
            values: [3000, 7000, 28771],
            labels: ['Bonus: 3 000€', 'Prime: 7 000€', 'Net: 28 771€'],
            hoverinfo: 'label+percent',
            hole: 0.7,
            type: 'pie'
        }];
    
        var layout = {
            title: 'Salaire',
            annotations:[{
                font: {size: 10},
                showarrow: false,
                text: 'Salaire net: <br>38 77'+nbChart+' €',
            }],
            height: 400,
            width: 400,
            showlegend: true,
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
        };
    
        Plotly.newPlot(
            'chart',
            data,
            layout,
        )
        
        chartToImage(nbChart, total).then(
            function() {
                resolve2();
            }
        );
    });
}
function chartToImage (nbChart, total) {
    return new Promise(function(resolve3, reject3) {
        Plotly.toImage("chart", {format: 'png'}).then(function (dataURL) {
            $.ajax({
                url: '/trame/donwloadChartImage',
                method: 'POST',
                data: {
                    image: dataURL,
                    nbChart: nbChart,
                },
                success: function () {
                    resolve3();
                },
            });
        });
        
        progressBar(nbChart, total).then(
            function() {
                resolve3();
            }
        );
    });
}


function progressBar (iteration, total) {
    return new Promise(function(resolve4, reject4) {
        var progressBar = Math.round($('.progress').length/total*iteration*100*100)/100;
        document.getElementById("progress-bar-chart").style.width = progressBar + "%";
        $("#progress-bar-chart").text(progressBar + "%");
    });
}

$(function() {
    let promise = Promise.resolve();
    const total = 10;
    $("#generateChartImage").on( "click", function() {
        for (let i = 1; i <= total; i++) {
            promise = promise.then(() => generateChart(i, total))
    
        }
    });
});
