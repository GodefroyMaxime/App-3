import Plotly from 'plotly.js-dist-min'

function generateImageChart() {
    return new Promise(function(resolve, reject) {

    });
}

$(function() {
    let promise = Promise.resolve();
    $("#generateChartImage"+info.matricule).on( "click", function() {
        promise = promise.then(() => generateChart(info))
    });
    $("#generateAllChartImage").on( "click", function() {
        promise = promise.then(() => generateChart(info))
    });
});
