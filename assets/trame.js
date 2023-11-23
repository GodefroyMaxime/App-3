import Plotly from 'plotly.js-dist-min'

function generateChart(test) {
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
            text: 'Salaire net: <br>38 77'+test+' €',
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
}

function chartToImage (test) {
    $.when(generateChart(test)).then(
        Plotly.toImage("chart", {format: 'png'}).then(function (dataURL) {
            $.ajax({
                url: '/trame/donwloadChartImage',
                method: 'POST',
                data: {
                    image: dataURL,
                    number: test,
                },
                success: function () {},
            });
        })
    );
}

$(function() {
    for (let i = 1; i <= 10; i++) {
        chartToImage(i);
    }
});