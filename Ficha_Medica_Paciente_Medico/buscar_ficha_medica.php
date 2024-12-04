<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Ficha Médica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .patient-info {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Buscar Ficha Médica del Paciente</h1>
    <form id="searchForm">
        <label for="rut">RUT del Paciente:</label>
        <input type="text" id="rut" name="rut" required placeholder="Ingrese el RUT del paciente">
        <input type="submit" value="Buscar Ficha Médica">
    </form>

    <div id="patientInfo" class="patient-info" style="display: none;">
        <h3>Datos del Paciente</h3>
        <p><strong>Nombre:</strong> <span id="patientName"></span></p>
        <p><strong>Género:</strong> <span id="patientGender"></span></p>
        <p><strong>Edad:</strong> <span id="patientAge"></span></p>
        <p><strong>Tipo de sangre:</strong> <span id="patientBloodType"></span></p>
        <p><strong>Condición:</strong> <span id="patientCondition"></span></p>
    </div>

    <script>
        // Simulación de base de datos de pacientes
        const pacientes = {
            "10.513.477-9": { nombre: "John Doe", genero: "Masculino", edad: 40, sangre: "A+", condicion: "Diabetes Tipo 2" },
            "12.345.678-9": { nombre: "Jane Smith", genero: "Femenino", edad: 35, sangre: "B+", condicion: "Hipertensión" },
            // Agrega más pacientes si es necesario
        };

        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Evita el envío del formulario

            const rut = document.getElementById('rut').value;  // Obtiene el RUT ingresado
            const paciente = pacientes[rut];  // Busca el paciente en la base de datos

            if (paciente) {
                // Si el paciente es encontrado, muestra los datos
                document.getElementById('patientInfo').style.display = 'block';
                document.getElementById('patientName').textContent = paciente.nombre;
                document.getElementById('patientGender').textContent = paciente.genero;
                document.getElementById('patientAge').textContent = paciente.edad;
                document.getElementById('patientBloodType').textContent = paciente.sangre;
                document.getElementById('patientCondition').textContent = paciente.condicion;
            } else {
                // Si el paciente no es encontrado
                alert('Paciente no encontrado');
                document.getElementById('patientInfo').style.display = 'none';  // Oculta los datos
            }
        });
    </script>
</body>
</html>
