<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>@yield('title', 'Documento Médico')</title>
		<style>
			body {
				font-family: DejaVu Sans, sans-serif;
				font-size: 12px;
				line-height: 1.4;
				color: #333;
			}
			header {
				border-bottom: 2px solid #2c3e50;
				padding-bottom: 10px;
				margin-bottom: 20px;
			}
			header h1 {
				font-size: 20px;
				margin: 0;
			}
			.clinic-info {
				font-size: 11px;
				color: #555;
			}
			footer {
				border-top: 1px solid #ccc;
				font-size: 10px;
				color: #777;
				text-align: center;
				padding-top: 10px;
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
			}
			table {
				width: 100%;
				border-collapse: collapse;
				margin-bottom: 15px;
			}
			table th,
			table td {
				border: 1px solid #ddd;
				padding: 6px;
				text-align: left;
			}
			table th {
				background-color: #f2f2f2;
			}
			.section-title {
				font-size: 14px;
				font-weight: bold;
				margin-top: 20px;
				margin-bottom: 8px;
				color: #2c3e50;
			}
		</style>
		@yield('styles')
	</head>
	<body>

		<header>
			<h1>{{ $clinicName }}</h1>
			<div class="clinic-info">
				Dirección:
				{{ $clinicAddress ?? 'No definida' }}
				<br>
				Teléfono:
				{{ $clinicPhone ?? 'No definido' }}
				<br>
				Email:
				{{ $clinicEmail ?? 'No definido' }}
				<br>
				Fecha de emisión:
				{{ now()->format('d/m/Y') }}
			</div>
		</header>

		<main>
			@yield('content')
		</main>

		<footer>
			Documento generado automáticamente por
			{{ config('app.name', 'MediApp') }}
			—
			{{ now()->format('d/m/Y H:i') }}
		</footer>

	</body>
</html>

