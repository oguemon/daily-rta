function plotHorizontalBar(plotarea)
{
    new Chart(document.getElementById(plotarea), {
        type: "horizontalBar",
        data: {
        datasets: [
            { label: "bad", data: [5], backgroundColor: "rgba(244, 143, 177, 0.6)" },
            { label: "better", data: [15], backgroundColor: "rgba(255, 235, 59, 0.6)" },
            { label: "good", data: [10], backgroundColor: "rgba(100, 181, 246, 0.6)" }
        ]
        },
        options: {
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