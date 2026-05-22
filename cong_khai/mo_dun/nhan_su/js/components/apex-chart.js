(function (window) {
  'use strict';

  function isNumberArray(arr) {
    return Array.isArray(arr) && arr.every(function (n) { return Number.isFinite(Number(n)); });
  }

  function normalizeSeries(series) {
    if (!Array.isArray(series)) {
      return [];
    }
    return series
      .map(function (item) {
        if (!item || typeof item !== 'object') {
          return null;
        }
        var data = Array.isArray(item.data) ? item.data.map(function (n) { return Number(n || 0); }) : [];
        return { name: String(item.name || ''), data: data };
      })
      .filter(function (item) {
        return item && isNumberArray(item.data) && item.data.length > 0;
      });
  }

  function normalizeLabels(labels) {
    if (!Array.isArray(labels)) {
      return [];
    }
    return labels.map(function (label) { return String(label || ''); });
  }

  function ApexChartComponent(el, config) {
    this.el = el;
    this.config = config || {};
    this.chart = null;
  }

  ApexChartComponent.prototype.render = function () {
    if (!this.el || typeof ApexCharts === 'undefined') {
      return false;
    }
    var options = Object.assign({}, this.config.options || {});
    options.chart = Object.assign({}, options.chart || {}, {
      type: this.config.type || (options.chart && options.chart.type) || 'line',
      height: Number(this.config.height || 260)
    });

    if (this.config.labels) {
      options.labels = normalizeLabels(this.config.labels);
    }
    if (this.config.categories) {
      options.xaxis = Object.assign({}, options.xaxis || {}, {
        categories: normalizeLabels(this.config.categories)
      });
    }

    var series = normalizeSeries(this.config.series);
    if (series.length === 0) {
      this.el.innerHTML = '<div class="text-secondary">Khong co du lieu bieu do</div>';
      return false;
    }

    this.chart = new ApexCharts(this.el, Object.assign({}, options, { series: series }));
    this.chart.render();
    return true;
  };

  ApexChartComponent.prototype.destroy = function () {
    if (this.chart && typeof this.chart.destroy === 'function') {
      this.chart.destroy();
    }
    this.chart = null;
  };

  window.NhanSuApexChart = ApexChartComponent;
})(window);
