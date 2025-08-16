@extends('pdf.layout')

@section('title', 'Receta Médica')

@section('content')

    <h2 style="text-align:center;">Receta Médica</h2>

    <div class="section-title">Datos del Paciente</div>
    <table>
        <tr>
            <th>Nombre</th>
            <td>{{ $prescription->appointment->patient->full_name }}</td>
        </tr>
        <tr>
            <th>Identificación</th>
            <td>{{ $prescription->appointment->patient->document }}</td>
        </tr>
        <tr>
            <th>Edad</th>
            <td>{{ $prescription->appointment->patient->age }} años</td>
        </tr>
    </table>

    <div class="section-title">Datos del Médico</div>
    <table>
        <tr>
            <th>Nombre</th>
            <td>{{ $prescription->appointment->doctor->user->full_name }}</td>
        </tr>
        <tr>
            <th>Especialidad</th>
            <td>{{ $prescription->appointment->doctor->specialty }}</td>

        </tr>

    </table>

    <div class="section-title">Medicamentos</div>
    <table>
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Dosis</th>
                <th>Frecuencia</th>
                <th>Duración</th>
                <th>Indicaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->items as $item)
                <tr>
                    <td>{{ $item->medication_name }}</td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->frequency }}</td>
                    <td>{{ $item->duration }}</td>
                    <td>{{ $item->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($prescription->notes)
        <div class="section-title">Notas</div>
        <p>{{ $prescription->notes }}</p>
    @endif

    <div style="margin-top:50px; text-align:right;">
        ___________________________<br>
        Firma del Médico
    </div>

@endsection