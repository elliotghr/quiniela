/**
 * Renderiza una gráfica de líneas de Highcharts de forma genérica,
 * para poder reutilizarla en cualquier vista sin repetir la configuración.
 *
 * @param {string} containerId - Id del elemento donde se dibuja la gráfica.
 * @param {Array}  categories  - Categorías del eje X.
 * @param {Array}  series      - Series de datos de Highcharts.
 * @param {Object} [options]   - Opciones opcionales de personalización.
 * @param {string} [options.type]          - Tipo de gráfica (default: 'line').
 * @param {string} [options.title]         - Título de la gráfica.
 * @param {string} [options.xAxisTitle]    - Título del eje X.
 * @param {string} [options.yAxisTitle]    - Título del eje Y.
 * @param {boolean} [options.allowDecimals] - Permitir decimales en el eje Y (default: false).
 */
function renderLineChart(containerId, categories, series, options) {
    options = options || {};

    if (!Array.isArray(categories) || !Array.isArray(series) || categories.length === 0 || series.length === 0) {
        console.warn('Graph data is not available');
        return;
    }

    return Highcharts.chart(containerId, {
        chart: {
            type: options.type || 'line'
        },

        title: {
            text: options.title || ''
        },

        xAxis: {
            categories: categories,
            title: {
                text: options.xAxisTitle || ''
            }
        },

        yAxis: {
            title: {
                text: options.yAxisTitle || ''
            },
            allowDecimals: options.allowDecimals !== undefined ? options.allowDecimals : false
        },

        series: series
    });
}
