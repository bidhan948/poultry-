<html>
    <head>
        <title>chart test</title>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <head>

    <body>

        <div id="chart"></div>

        <script>
            let options = {
                    chart: { type: 'line' },
                    series: [{ name: 'sales', data: [30,40,35,50,49,60,70,91,125] }],
                    xaxis: { categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]}
                },
                chart = new ApexCharts(document.querySelector("#chart"), options),
                logChart = () => console.log(chart),
                destroyChart = () => {
                    if (chart.ohYeahThisChartHasBeenRendered) {
                        chart.destroy();
                        chart.ohYeahThisChartHasBeenRendered = false;
                    }
                };

            chart.render().then(() => chart.ohYeahThisChartHasBeenRendered = true);

        </script>

        <button onclick="logChart()">Log chart</button>
        <button onclick="destroyChart()">Destroy chart</button>

    </body>
</html>