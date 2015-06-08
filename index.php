<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>TGXQ2015 Net Value</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript"
	src="js/jquery-ui/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="js/highstocks.js"></script>
<script type="text/javascript" src="js/exporting.js"></script>
</head>
<body>
	<div id="container" style="height: 650px; min-width: 500px"></div>
	<script>
	  var fundName = "tgxq2015";
	  $(function () {
	    $.getJSON('data.php?d=' + fundName + '&callback=?', function (data) {
	        if (data.length <= 0) {
	            return;
	        }
	        data.sort(function(a,b) {return (a[0]<b[0])?-1:1;});
            var netValueBase = data[0][1];
	        var refIdxBase = data[0][2];
	        if (refIdxBase == 0) {
	            return;
	        }
	        var netValueD = [], refIdxD = [];
	        for (var i = 0; i < data.length; i++) {
	            if (data[i][0] == "" || data[i][1] == "" || data[i][2] == "") continue;
	            var d = $.datepicker.parseDate('yymmdd', data[i][0]);
	            var dut = d.getTime() + 8 * 3600000;
	            // netValueD
	            netValueD[i] = {};
	            netValueD[i].x = dut;
	            netValueD[i].y = data[i][1];
	            netValueD[i].name = 'nv';
	            netValueD[i].absraw = data[i][1] / netValueBase;
				netValueD[i].abs = Highcharts.numberFormat(netValueD[i].absraw, 3);
	            netValueD[i].percent = Highcharts.numberFormat(netValueD[i].y * 100, 2);
	            if (i == 0) {
	                netValueD[i].percentToPrev = 0;
	            } else {
	                netValueD[i].percentToPrev = Highcharts.numberFormat((netValueD[i].absraw - netValueD[i - 1].absraw) * 100 / netValueD[i - 1].absraw, 2);
				}
	            // refIdxD
	            refIdxD[i] = {};
	            refIdxD[i].x = dut;
	            refIdxD[i].y = (data[i][2] - refIdxBase) / refIdxBase;
	            refIdxD[i].name = 'idx';
	            refIdxD[i].abs = data[i][2];
	            refIdxD[i].percent = Highcharts.numberFormat(refIdxD[i].y * 100, 2);
	            if (i == 0) {
	                refIdxD[i].percentToPrev = 0;
	            } else {
	                refIdxD[i].percentToPrev = Highcharts.numberFormat((data[i][2] - data[i - 1][2]) * 100 / data[i - 1][2], 2);
	            }
	        }

	        // Create the chart
	        $('#container').highcharts('StockChart', {
	            rangeSelector: {
	                selected: 1
	            },
	            colors: [
	                '#910000',
	                '#2f7ed8',
	                '#0d233a',
	                '#8bbc21',
	                '#1aadce',
	                '#492970',
	                '#f28f43',
	                '#77a1e5',
	                '#c42525',
	                '#a6c96a'],

	            title: {
	                text: 'TGXQ2015 Net Value'
	            },

	            yAxis: [{
	                title: {
	                    text: 'Net Value'
	                },
	                lineWidth: 2,
	                labels: {
	                    formatter: function () {
	                        return this.value * 100 + '%';
	                    }
	                }
	            }],

	            series: [{
	                name: '净　　值',
	                data: netValueD,
	                marker: {
	                    enabled: true,
	                    radius: 3
	                },
	                tooltip: {
	                    valueDecimals: 3
	                }
	            }, {
	                name: '上证指数',
	                data: refIdxD,
	                marker: {
	                    enabled: true,
	                    radius: 3
	                },
	                tooltip: {
	                    valueDecimals: 3
	                }
	            }],

	            tooltip: {
	                formatter: function () {
	                    var s = '<b>' + Highcharts.dateFormat("%Y-%m-%d", this.x) + '</b><br/>';
	                    s += '<br/>------------------------';
	                    for (var i = 0; i < this.points.length; i++) {
	                        var pointType = this.points[i].point.options.name;
	                        if (pointType == 'nv' || pointType == 'idx') {
	                            if (pointType == 'nv') {
	                                s += '<br/>净　　值：';
	                            } else if (pointType == 'idx') {
	                                s += '<br/>上证指数：';
	                            }

	                            var abs = this.points[i].point.options.abs;
	                            var percent = this.points[i].point.options.percentToPrev;
	                            s += abs + ' (<span style="color:';
	                            if (percent < 0) {
	                                s += 'green';
	                            } else {
	                                s += 'red';
	                            }
	                            s += '">' + percent + '%</span>)';
	                        } else {
	                            continue;
	                        }
	                    }
	                    s += '<br/>------------------------';
	                    return s;
	                }
	            },

	            legend: {
	                enabled: true
	            },
	        });
	    });
	});
	</script>
</body>
</html>
