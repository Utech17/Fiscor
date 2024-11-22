<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario'];
$idRol = isset($_SESSION['ID_Rol']) ? $_SESSION['ID_Rol'] : 0;

// Desconectar, si le da clic en Volver
if (isset($_GET['Volver'])) {
    session_destroy();
    echo "<script>
        location.href='../index.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SAE Asist Escolar</title>
    <link rel="stylesheet" href="../vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vista/css/dataTables.bootstrap5.css">
    <link rel="website icon" type="png" href="../vista/img/logo2.png">
    <link rel="stylesheet" type="text/css" href="../vista/css/estilosinicio.css">
    <script src="../vista/js/chart.js"></script>
</head>
<body>
    <?php imprimirTopBar($nombreUsuario); ?>
    <div class="contenedor">
        <div class="barra-lateral" id="barra-lateral">
            <?php imprimirBarraLateral($idRol) ?>
        </div>
        <div class="contenido">
            <h1>Panel de control</h1>
        </div>
    </div>     
    <div class="container-inicio">
        <div class="row contenedor-card px-6 pt-5">
            <div class="col-md-4">
                <div class="card text-center mb-3" style="background-color: #007bff; color: white;">
                    <div class="card-header">Total Proyectos</div>
                    <div class="card-body">
                        <h5 class="card-title"><span id="proyectos"><?php echo htmlspecialchars($totalpro); ?></span></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3" style="background-color: #007bff; color: white;">
                    <div class="card-header">Total Presupuesto</div>
                    <div class="card-body">
                        <h5 class="card-title"><span id="presupuestos"><?php echo htmlspecialchars($totalpre); ?></span></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center mb-3" style="background-color: #007bff; color: white;">
                    <div class="card-header">Total Gastos</div>
                    <div class="card-body">
                        <h5 class="card-title"><span id="idVendidos3"><?php echo htmlspecialchars($totalg); ?></span></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="contenedor-canvas px-6 pt-5">
            <center><form method="GET" id="formulario-proyecto" class="text-center mb-4">
                <label for="proyecto" class="form-label"><h2>Presupuesto por categorías del Proyecto: </h2></label>
                <select name="id_proyecto" id="proyecto" class="form-select w-auto d-inline-block">
                    <!-- Añade las opciones de proyectos dinámicamente desde PHP -->
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?php echo $proyecto['ID_Proyecto']; ?>" <?php if ($id_proyecto == $proyecto['ID_Proyecto']) echo 'selected'; ?>>
                            <?php echo $proyecto['Nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form></center>
            <div class="d-flex justify-content-center">
                <canvas id="myPolarAreaChart" width="50" height="50"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('myPolarAreaChart').getContext('2d');
            var myPolarAreaChart;

            function actualizarGrafico(data) {
                const labels = data.map(item => item.categoria);
                const dataSet = data.map(item => item.presupuesto);

                if (myPolarAreaChart) {
                    myPolarAreaChart.destroy(); // Destruir gráfico existente para actualizarlo
                }

                myPolarAreaChart = new Chart(ctx, {
                    type: 'polarArea',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Presupuesto de la Categoría',
                            data: dataSet,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(199, 199, 199, 0.2)',
                                'rgba(83, 102, 255, 0.2)',
                                'rgba(255, 159, 192, 0.2)',
                                'rgba(64, 159, 255, 0.2)',
                                'rgba(192, 255, 99, 0.2)',
                                'rgba(255, 64, 159, 0.2)',
                                'rgba(102, 255, 102, 0.2)',
                                'rgba(255, 206, 132, 0.2)',
                                'rgba(159, 64, 255, 0.2)',
                                'rgba(86, 255, 206, 0.2)',
                                'rgba(99, 255, 132, 0.2)',
                                'rgba(192, 75, 75, 0.2)',
                                'rgba(132, 206, 255, 0.2)',
                                'rgba(235, 54, 162, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(199, 199, 199, 1)',
                                'rgba(83, 102, 255, 1)',
                                'rgba(255, 159, 192, 1)',
                                'rgba(64, 159, 255, 1)',
                                'rgba(192, 255, 99, 1)',
                                'rgba(255, 64, 159, 1)',
                                'rgba(102, 255, 102, 1)',
                                'rgba(255, 206, 132, 1)',
                                'rgba(159, 64, 255, 1)',
                                'rgba(86, 255, 206, 1)',
                                'rgba(99, 255, 132, 1)',
                                'rgba(192, 75, 75, 1)',
                                'rgba(132, 206, 255, 1)',
                                'rgba(235, 54, 162, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        aspectRatio: 4,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        var dataset = tooltipItem.dataset;
                                        var total = dataset.data.reduce((acc, value) => acc + value, 0);
                                        var value = dataset.data[tooltipItem.dataIndex];
                                        var percentage = ((value / total) * 100).toFixed(2);
                                        return `${dataset.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1000, // Duración de la animación en milisegundos
                            easing: 'easeOutBounce' // Tipo de animación, puedes cambiarlo según tu preferencia
                        }
                    }
                });
            }

            // Inicializar el gráfico con los datos actuales
            const initialData = <?php echo isset($datosJSON2) ? $datosJSON2 : '[]'; ?>;
            actualizarGrafico(initialData);

            // Añadir el evento de cambio al select del proyecto
            document.getElementById('proyecto').addEventListener('change', function() {
                const id_proyecto = this.value;

            // Realizar la solicitud AJAX
            fetch(`../controlador/inicio_controlador.php?id_proyecto=${id_proyecto}`)
                .then(response => response.json())
                .then(data => {
                    actualizarGrafico(data); // Actualizar el gráfico con los nuevos datos
                })
                .catch(error => console.error('Error en la solicitud AJAX:', error));
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hamburguesa = document.getElementById('hamburguesa');
            var barraLateral = document.getElementById('barra-lateral');
            
            hamburguesa.addEventListener('click', function() {
                barraLateral.classList.toggle('show'); // Alterna la visibilidad de la barra lateral
                
                // Si la barra lateral se muestra, mueve el ícono de hamburguesa
                if (barraLateral.classList.contains('show')) {
                    hamburguesa.style.transform = 'translateX(150px)'; // Desplaza el ícono 200px a la derecha
                } else {
                    hamburguesa.style.transform = 'translateX(0)'; // Restaura la posición original
                }
            });
        });
    </script>
    <script src="../vista/js/bootstrap.bundle.min.js"></script>
</body>
</html>