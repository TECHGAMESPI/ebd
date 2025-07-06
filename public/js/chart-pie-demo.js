// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
//Chart.defaults.global.defaultFontColor = '#292b2c';

// Pie Chart Example
var andre = document.getElementById('andre');
var lucas = document.getElementById('lucas');
var simaozelote = document.getElementById('simaozelote');
var mateus = document.getElementById('mateus');
var tome = document.getElementById('tome');
var oficiaiseprofessores = document.getElementById('oficiaiseprofessores');
var catecumenos = document.getElementById('catecumenos');
var daniel = document.getElementById('daniel');
var jonas = document.getElementById('jonas');


var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["André", "Lucas", "Simão Zelote", "Mateus", "Tomé", "Oficiais e Professores", "Catecúmenos", "Daniel", "jonas"],
        datasets: [{
            label: 'Turmas',
            data: [andre.value, lucas.value, simaozelote.value, mateus.value, tome.value, oficiaiseprofessores.value, catecumenos.value, daniel.value, jonas.value],
        }],
    },
});
