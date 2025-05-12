document.addEventListener('DOMContentLoaded', function () {
    const carreraSelect = document.getElementById("carrera_carrera_id");
    const usuarioSelect = document.getElementById("usuario_usuario_id");
    const periodoSelect = document.getElementById("periodo_periodo_id");

    // Carga el horario si ya hay valores predeterminados seleccionados
    if (periodoSelect.value && usuarioSelect.value && carreraSelect.value) {
        filtrarHorario();  // Llama a filtrarHorario() si hay carrera, usuario y periodo seleccionados
    }

    // Escucha los cambios en los selects
    ['periodo_periodo_id', 'usuario_usuario_id', 'carrera_carrera_id'].forEach(id =>
        document.getElementById(id).addEventListener('change', filtrarHorario)
    );

    // Define los horarios y días
    const horas = [
        { id: 1, descripcion: '07:00 - 08:00' },
        { id: 2, descripcion: '08:00 - 09:00' },
        { id: 3, descripcion: '09:00 - 10:00' },
        { id: 4, descripcion: '10:00 - 11:00' },
        { id: 5, descripcion: '11:00 - 12:00' },
        { id: 6, descripcion: '12:00 - 13:00' },
        { id: 7, descripcion: '13:00 - 14:00' },
        { id: 8, descripcion: '14:00 - 15:00' },
        { id: 9, descripcion: '15:00 - 16:00' },
        { id: 10, descripcion: '16:00 - 17:00' },
        { id: 11, descripcion: '17:00 - 18:00' },
        { id: 12, descripcion: '18:00 - 19:00' },
        { id: 13, descripcion: '19:00 - 20:00' },
        { id: 14, descripcion: '20:00 - 21:00' },
    ];

    const dias = [
        { id: 1, descripcion: 'Lunes' },
        { id: 2, descripcion: 'Martes' },
        { id: 3, descripcion: 'Miércoles' },
        { id: 4, descripcion: 'Jueves' },
        { id: 5, descripcion: 'Viernes' },
        { id: 6, descripcion: 'Sábado' },
    ];

    async function filtrarHorario() {
        const periodo = periodoSelect.value;
        const usuarioId = usuarioSelect.value;
        const carrera = carreraSelect.value;

        // Solo se procede si hay un periodo, usuario y carrera seleccionados
        if (!periodo || !usuarioId || !carrera) {
            return;
        }

        try {
            const response = await fetch('../../models/cargar_horario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ periodo, usuarioId, carrera }),
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            const data = await response.json();

            console.log("Datos recibidos del servidor:", data);

            if (data.length === 0) {
                Swal.fire({
                    title: 'No se encontraron horarios',
                    text: 'La tabla está disponible para registrar.',
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                });
                mostrarTablaVacia();
            } else {
                mostrarTabla(data);
            }
        } catch (error) {
            console.error('Error al filtrar el horario:', error);
            Swal.fire({
                title: 'No se encontraron datos',
                text: 'La tabla está disponible para registrar.',
                icon: 'info',
                confirmButtonText: 'Aceptar',
            });
            mostrarTablaVacia();
        }
    }

    function generarTablaHTML(data) {
        let tableHTML = `
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Hora</th>
                        ${dias.map(d => `<th>${d.descripcion}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${horas
                        .map(hora => {
                            return `<tr>
                                <td>${hora.descripcion}</td>
                                ${dias.map(dia => {
                                    const evento = data.find(item => item.horas_horas_id == hora.id && item.dias_id == dia.id);
                                    return `<td>${evento ? `${evento.materia}<br>${evento.grupo}<br>${evento.salon}` : ''}</td>`;
                                }).join('')}
                            </tr>`;
                        })
                        .join('')}
                </tbody>
            </table>`;

        return tableHTML;
    }

    function mostrarTabla(data) {
        const tablaContenedor = document.querySelector('.schedule-container .table-responsive');
        if (tablaContenedor) {
            tablaContenedor.innerHTML = generarTablaHTML(data);
        } else {
            console.error("Error: No se encontró el contenedor de la tabla.");
        }
    }

    function mostrarTablaVacia() {
        mostrarTabla([]);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var barChartCtn = document.querySelector("#barChart");
  
    if (barChartCtn) {
        var nombreDocente = barChartCtn.getAttribute("data-docente") || "No definido";
        console.log("Nombre del docente en JavaScript:", nombreDocente); // 🔍 Debug
  
        var tutorias = parseInt(barChartCtn.getAttribute("data-tutorias") || 0);
        var apoyo = parseInt(barChartCtn.getAttribute("data-apoyo") || 0);
        var frente = parseInt(barChartCtn.getAttribute("data-frente") || 0);
        // ✅ Calculamos el total de horas
        var totalHoras = tutorias + apoyo + frente;
  
        // 🎨 Define los colores de la gráfica y la leyenda
        var chartColors = ["#008FFB", "#00E396", "#FEB019"]; // Azul, Verde, Amarillo
  
        var barChartoptions = {
            series: [
                { name: "Tutorías", data: [tutorias] },
                { name: "Horas de Apoyo", data: [apoyo] },
                { name: "Horas Frente al Grupo", data: [frente] }
            ],
            chart: {
                type: "bar",
                height: 150,
                stacked: true,
                columnWidth: "70%",
                zoom: { enabled: false },
                toolbar: { enabled: false },
            },
            theme: { mode: colors.chartTheme },
            dataLabels: { enabled: true },
            plotOptions: { bar: { horizontal: true, columnWidth: "30%" } },
            xaxis: {
                categories: [nombreDocente], // 🔥 Aquí debería aparecer el nombre correcto
                labels: {
                    colors: colors.mutedColor,
                    fontFamily: base.defaultFontFamily,
                },
                axisBorder: { show: false },
            },
            yaxis: {
                labels: {
                    colors: colors.mutedColor,
                    fontFamily: base.defaultFontFamily,
                },
            },
            legend: {
                position: "bottom",
                fontFamily: base.defaultFontFamily,
                labels: {
                    colors: chartColors, // 🔹 La leyenda usa los mismos colores
                    useSeriesColors: false
                },
            },
            fill: { opacity: 1, colors: chartColors }, // 🔹 Los colores de las barras coinciden con la leyenda
            grid: {
                borderColor: colors.borderColor,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } },
                padding: { left: 10, right: 10 },
            },
        };
  
        var barChart = new ApexCharts(barChartCtn, barChartoptions);
        barChart.render();
               // ✅ Mostrar el total de horas debajo de la gráfica
               document.getElementById("total-horas").innerHTML = `
               Total de Horas: <span style="color: #ff5733;">${totalHoras}</span>
           `;
    }
  });


document.getElementById("downloadPDF").addEventListener("click", function() {
    // Configuración inicial (indicador de carga, etc.)
    const button = document.querySelector('.pdf-container');
    const loadingIndicator = document.createElement('div');
    loadingIndicator.style.position = 'fixed';
    loadingIndicator.style.top = '50%';
    loadingIndicator.style.left = '50%';
    loadingIndicator.style.transform = 'translate(-50%, -50%)';
    loadingIndicator.style.padding = '15px';
    loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.7)';
    loadingIndicator.style.color = 'white';
    loadingIndicator.style.borderRadius = '5px';
    loadingIndicator.style.zIndex = '9999';
    loadingIndicator.textContent = 'Generando PDF para móviles...';
    document.body.appendChild(loadingIndicator);
    
    button.style.display = 'none';
    
    const element = document.getElementById("contenedor");
    const originalStyles = {
        overflow: element.style.overflow,
        width: element.style.width,
        margin: element.style.margin,
        display: element.style.display,
        fontSize: element.style.fontSize
    };
    
    // Aplicar estilos temporales para móviles
    element.style.overflow = 'visible';
    element.style.width = '100%';
    element.style.margin = '0 auto';
    element.style.display = 'flex';
    element.style.justifyContent = 'center';
    element.style.flexDirection = 'column';
    element.style.alignItems = 'center';
    element.style.fontSize = '18px'; // Tamaño de fuente aumentado

    // Configuración optimizada para móviles
    const options = {
        margin: [5, 5, 5, 5],
        filename: 'horario_isc.pdf',
        image: { 
            type: 'jpeg', 
            quality: 1.0
        },
        html2canvas: {
            scale: window.innerWidth < 768 ? 1.5 : 1, // Escala mayor para móviles
            scrollX: 0,
            scrollY: 0,
            useCORS: true,
            allowTaint: true,
            letterRendering: true,
            windowWidth: window.innerWidth < 768 ? document.documentElement.scrollWidth * 1.5 : document.documentElement.scrollWidth,
            windowHeight: document.documentElement.scrollHeight,
            onclone: function(clonedDoc) {
                // Estilos específicos para móviles en el clon
                const container = clonedDoc.getElementById("contenedor");
                container.style.width = '100%';
                container.style.padding = '10px';
                
                // Ajustar tabla para móviles
                const table = clonedDoc.querySelector('table');
                table.style.width = '95%'; // Casi ancho completo
                table.style.margin = '0 auto';
                table.style.fontSize = window.innerWidth < 768 ? '16px' : '14px';
                table.style.borderCollapse = 'collapse';
                
                // Ajustar celdas
                const cells = clonedDoc.querySelectorAll('th, td');
                cells.forEach(cell => {
                    cell.style.padding = window.innerWidth < 768 ? '12px 8px' : '8px';
                    cell.style.border = '1px solid #000';
                    cell.style.textAlign = 'center';
                });
                
                // Ajustar encabezados
                const headers = clonedDoc.querySelectorAll('th');
                headers.forEach(header => {
                    header.style.fontSize = window.innerWidth < 768 ? '18px' : '16px';
                    header.style.padding = '12px 8px';
                });
                
                // Formatear horas
                const timeCells = clonedDoc.querySelectorAll('td:first-child');
                timeCells.forEach(cell => {
                    cell.style.fontWeight = 'bold';
                    cell.style.fontSize = window.innerWidth < 768 ? '16px' : '14px';
                });
            }
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'landscape',
            compress: false
        }
    };

    setTimeout(function() {
        html2pdf()
            .set(options)
            .from(element)
            .save()
            .then(function() {
                // Restaurar estilos originales
                Object.keys(originalStyles).forEach(prop => {
                    element.style[prop] = originalStyles[prop];
                });
                button.style.display = 'block';
                document.body.removeChild(loadingIndicator);
            })
            .catch(function(error) {
                console.error('Error al generar PDF:', error);
                Object.keys(originalStyles).forEach(prop => {
                    element.style[prop] = originalStyles[prop];
                });
                button.style.display = 'block';
                document.body.removeChild(loadingIndicator);
                Swal.fire('Error', 'No se pudo generar el PDF', 'error');
            });
    }, 1000);
});

// CSS adicional específico para móviles
const mobileStyles = document.createElement('style');
mobileStyles.textContent = `
    @media print {
        body {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            zoom: 100% !important;
        }
        #contenedor {
            width: 100% !important;
            margin: 0 auto !important;
            padding: 10px !important;
        }
        table {
            width: 95% !important;
            margin: 0 auto !important;
            font-size: 16px !important;
            border-collapse: collapse !important;
        }
        th {
            font-size: 18px !important;
            padding: 12px 8px !important;
        }
        td {
            padding: 12px 8px !important;
            text-align: center !important;
        }
        td:first-child {
            font-weight: bold !important;
            font-size: 16px !important;
        }
    }
    
    @media screen and (max-width: 768px) {
        body {
            font-size: 18px !important;
        }
        .table-responsive {
            width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        table {
            width: 95% !important;
            margin: 20px auto !important;
            font-size: 16px !important;
        }
        th {
            font-size: 18px !important;
            padding: 12px 8px !important;
        }
        td {
            padding: 12px 8px !important;
            min-width: 80px !important;
        }
    }
    
    /* Estilos base mejorados */
    table {
        width: 95%;
        margin: 20px auto;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        text-align: center;
    }
    th {
        font-weight: bold;
    }
`;
document.head.appendChild(mobileStyles);