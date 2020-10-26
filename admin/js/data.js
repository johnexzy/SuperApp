// const axios = require('axios');
class ApiData {
    constructor(baseUrl) {
        this.baseUrl = baseUrl
    }
    async getData(endpoint) {
        await fetch(this.baseUrl + endpoint)
            .then(res => res.text())
            .then(data => data)

    }
}
let music, movies, series;
music = $('#musicCount').val()
movies = $('#moviesCount').val()
series = $('#seriesCount').val()
// alert(music+" "+series+" "+movies)
var data = {
    labels: ["MUSIC", "SERIES", "MOVIES"],
    datasets: [{
        label: 'Number of Records',
        data: [music, series, movies],
        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            // 'rgba(75, 192, 192, 0.2)',
            // 'rgba(153, 102, 255, 0.2)',
            // 'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            // 'rgba(75, 192, 192, 1)',
            // 'rgba(153, 102, 255, 1)',
            // 'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        fill: false
    }]
};
var options = {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    legend: {
        display: false
    },
    elements: {
        point: {
            radius: 0
        }
    }

};
var doughnutPieData = {
    labels: ['MUSIC', 'SERIES', 'MOVIES'],
    datasets: [{
        data: [music, series, movies],
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
        ],
        borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
        ],
    }],
}
var doughnutPieOptions = {
    responsive: true,
    animation: {
        animateScale: true,
        animateRotate: true
    }
};
// Get context with jQuery - using jQuery's .get() method.
if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: data,
        options: options
    });
}


if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: doughnutPieData,
        options: doughnutPieOptions
    });
}
// let music = new ApiData('/api/v1/')
// console.log(music.getData('music/count'))