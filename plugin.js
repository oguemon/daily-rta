(function(Chart) {
  var cloneArray = function(srcAry) {
    var dstAry = [];
    var length = srcAry.length;

    for (var i = 0; i < length; i++) {
      dstAry.push(srcAry[i]);
    }
    return dstAry;
  };

  var setOriginalData = function(data) {
    data.originalData = data.datasets.map(function(dataset) {
      return cloneArray(dataset.data);
    });
  };

  // set calculated rate (xx%) to data.calculatedData
  var calculateRate = function(data) {
    var visibles = data.datasets.map(function(dataset) {
      if (!dataset._meta) return true;

      for (var i in dataset._meta) {
        return !dataset._meta[i].hidden;
      }
    });

    var totals = Array.apply(null, new Array(data.datasets[0].data.length)).map(function(el, i) {
      return data.datasets.reduce(function(sum, dataset, j) {
        var key = dataset.stack;
        if (!sum[key]) sum[key] = 0;
        sum[key] += dataset.data[i] * visibles[j];

        return sum;
      }, {});
    });

    data.calculatedData = data.datasets.map(function(dataset, i) {
      return dataset.data.map(function(val, i) {
        var total = totals[i][dataset.stack];
        // return val && total ? Math.round(val * 1000 / total) / 10 : 0;
        return val && total ? Math.round(val * 240 / total) / 10 : 0;
      });
    });
  };

  var tooltipLabel = function(tooltipItem, data) {
    var datasetIndex = tooltipItem.datasetIndex;
    var index = tooltipItem.index;
    var datasetLabel = data.datasets[datasetIndex].label || "";
    var originalValue = data.originalData[datasetIndex][index];
    var rateValue = data.calculatedData[datasetIndex][index];

    var timestring = '';
    if (originalValue > 3600) {
      timestring += (originalValue - (originalValue % 3600)) / 3600 + '時間';
      originalValue = originalValue % 3600;
    }
    if (originalValue > 60) {
      timestring += (originalValue - (originalValue % 60)) / 60 + '分';
      originalValue = originalValue % 60;
    }
    if (originalValue > 0) {
      timestring += originalValue + '秒';
    }

    return datasetLabel + ": " + timestring;
  };

  var reflectData = function(srcData, datasets) {
    if (!srcData) return;

    srcData.forEach(function(data, i) {
      datasets[i].data = data;
    });
  };

  var Stacked100Plugin = {
    id: "stacked100",

    beforeInit: function(chartInstance, pluginOptions) {
      if (!pluginOptions.enable) return;

      var xAxes = chartInstance.options.scales.xAxes;
      var yAxes = chartInstance.options.scales.yAxes;
      var isVertical = chartInstance.config.type === "bar" || chartInstance.config.type === "line";

      [xAxes, yAxes].forEach(function(axes) {
        axes.forEach(function(hash) {
          hash.stacked = true;
        });
      });
      (isVertical ? yAxes : xAxes).forEach(function(hash) {
        hash.ticks.min = 0;
        hash.ticks.max = 24;
        //hash.ticks.max = 100;
      });

      chartInstance.options.tooltips.callbacks.label = tooltipLabel;
    },

    beforeDatasetsUpdate: function(chartInstance, pluginOptions) {
      if (!pluginOptions.enable) return;

      setOriginalData(chartInstance.data);
      calculateRate(chartInstance.data);
      reflectData(chartInstance.data.calculatedData, chartInstance.data.datasets);
    },

    afterDatasetsUpdate: function(chartInstance, pluginOptions) {
      if (!pluginOptions.enable) return;

      reflectData(chartInstance.data.originalData, chartInstance.data.datasets);
    }
  };

  Chart.pluginService.register(Stacked100Plugin);
}.call(this, Chart));