import './bootstrap';

import '../sass/app.scss';

import $ from 'jquery';
window.$ = window.jQuery = $;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

import Swal from 'sweetalert2';
window.Swal = Swal;

import Chart from 'chart.js/auto';
window.Chart = Chart;
