<?php

return [
    'days' => [
        'monday' => 'Lunes',
        'tuesday' => 'Martes',
        'wednesday' => 'Miércoles',
        'thursday' => 'Jueves',
        'friday' => 'Viernes',
        'saturday' => 'Sábado',
        'sunday' => 'Domingo',
    ],
    'appointments' => [
        'doctor_not_available' => 'El doctor no está disponible en el día seleccionado.',
        'doctor_has_conflicting_appointment' => 'El doctor tiene una cita en conflicto en este horario.',
        'appointment_confirmed' => 'Cita confirmada',
        'doctor_is_bussy' => 'Este horario ya está ocupado.',
        'status' => [
            'pending' => 'Pendiente',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'confirmed' => 'Confirmada',
        ],
        'patient_id' => 'Paciente',
        'doctor_id' => 'Doctor',
        'date_time' => 'Fecha y hora',
        'status_field' => 'Estado',
        'reason' => 'Motivo',
        'appointment' => 'Cita',
        'doctor_configuration' => 'Configuración del doctor',
        'default_appointment_duration' => 'Duración de cita por defecto',
        'default_appointment_duration_help' => 'Duración de cita por defecto en minutos',
        'doctor_configuration_help' => 'Configuración del doctor',
    ],
    'notifications' => [
        'appointment_scheduled' => 'Nueva Cita agendada',
        'appointment_created' => 'Nueva Cita creada',
        'appointment_updated' => 'Cita actualizada',
        'appointment_deleted' => 'Cita eliminada',
        'appointment_confirmed' => 'Cita confirmada',
        'appointment_canceled' => 'Cita cancelada',
        'appointment_rescheduled' => 'Cita reprogramada',
        'appointment_notification' => 'Notificación de cita',
        'appointment_notification_message' => 'Tienes una nueva cita programada.',
        'appointment_notification_action' => 'Ver cita',
        'appointment_notification_thanks' => 'Gracias por usar nuestra aplicación!',
        'appointment_notification_greeting' => 'Hola!',
        'appointment_patient_notification_line1' => 'Tienes una nueva cita programada con el doctor :doctor con la especialidad :specialization',
        'appointment_patient_notification_line2' => 'El doctor te atenderá el',
        'appointment_doctor_notification_line1' => 'Tienes una nueva cita programada con el paciente :patient',
        'appointment_doctor_notification_line2' => 'El paciente te visitará el',
        'appointment_doctor_notification_action_line' => 'Ver cita',
        'appointment_doctor_notification_thanks' => 'Gracias por usar nuestra aplicación!',
    ],
    'patients' => [
        'name' => 'Nombre',
        'lastname' => 'Apellido',
        'document' => 'Documento',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono',
        'address' => 'Dirección',
        'dob' => 'Fecha de nacimiento',
        'gender' => 'Género'
    ]

];