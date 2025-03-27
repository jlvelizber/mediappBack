<?php

return [
    'days' => [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ],
    'appointments' => [
        'doctor_not_available' => 'The doctor is not available on the selected day.',
        'doctor_has_conflicting_appointment' => 'The doctor has a conflicting appointment at this time.',
        'appointment_confirmed' => 'Appointment confirmed',
        'doctor_is_bussy' => 'This time is already taken.'
    ],
    'notifications' => [
        'appointment_scheduled' => 'New Appointment scheduled',
        'appointment_created' => 'New Appointment created',
        'appointment_updated' => 'Appointment updated',
        'appointment_deleted' => 'Appointment deleted',
        'appointment_confirmed' => 'Appointment confirmed',
        'appointment_canceled' => 'Appointment canceled',
        'appointment_rescheduled' => 'Appointment rescheduled',
        'appointment_notification' => 'Appointment Notification',
        'appointment_notification_message' => 'You have a new appointment scheduled.',
        'appointment_notification_action' => 'View appointment',
        'appointment_notification_thanks' => 'Thank you for using our application!',
        'appointment_notification_greeting' => 'Hello!',
        'appointment_patient_notification_line1' => 'You have a new appointment scheduled with doctor :doctor with specialization :specialization',
        'appointment_patient_notification_line2' => 'The doctor will meet you on',
        'appointment_notification_line3' => 'will meet you on',
        'appointment_doctor_notification_line1' => 'You have a new appointment scheduled with patient :patient',
        'appointment_doctor_notification_line2' => 'The patient will visit you on',
        'appointment_doctor_notification_action_line' => 'View appointment',
        'appointment_doctor_notification_thanks' => 'Thank you for using our application!',
    ],
    'patients' => [
        'name' => 'Name',
        'lastname' => 'Lastname',
        'document' => 'Document',
        'email' => 'Email',
        'phone' => 'Phone',
        'address' => 'Address',
        'dob' => 'Date of birth',
        'gender' => 'Gender'
    ]
];