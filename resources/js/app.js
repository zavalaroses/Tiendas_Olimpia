import './bootstrap';

import Alpine from 'alpinejs';
import 'bootstrap/dist/css/bootstrap.min.css'; 
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap; 

// 👉 importa jQuery primero
import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery-validation';

// Núcleo de DataTables
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

// Responsive
import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// Buttons
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';

// Extensiones de Buttons
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';
import 'datatables.net-buttons/js/buttons.colVis.js';

// Dependencias de exportación
import jszip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// Necesario para que PDFMake funcione
pdfMake.vfs = pdfFonts.vfs;
window.JSZip = jszip;

// Bootstrap 5 Popover y Tooltip
import { Popover, Tooltip } from 'bootstrap';
window.Popover = Popover;
window.Tooltip = Tooltip;

// Inicializa Alpine
window.Alpine = Alpine;
Alpine.start();
