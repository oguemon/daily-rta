function plotHorizontalBar(plotarea , datasets)
{
    new Chart(document.getElementById(plotarea), {
        type: "horizontalBar",
        data: datasets,
        options: {
            legend: {
                display: false
            },
            tooltips: {
                mode: 'nearest'
            },
            scales: {
              xAxes: [{
                display: true,
                stacked: false,
                ticks: {
                  stepSize: 2
                },
                gridLines: {
                  display: false
                }
              }],
              yAxes: [{
                gridLines: {
                  drawBorder: false
                }
              }]
            },
            plugins: {
                stacked100: { enable: true }
            }
        }
    });
}