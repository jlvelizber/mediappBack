# MediApp 

## Backend API Rest - Made with Laravel

## Product Scope

This API supports the doctor-only product for independent private practices.

### In Scope

- Authenticated doctor workflows.
- Doctor-owned resources (patients, appointments, medical records, prescriptions, availability).
- Doctor dashboard data and notifications tied to doctor activity.

### Out of Scope (handled in other products)

- Patient-facing portal APIs.
- Admin/business backoffice for multi-doctor organizations.
- Clinic/medical center domain management.
- Billing, subscriptions, and customer lifecycle management.

### Security Boundary

All doctor endpoints must enforce ownership by authenticated doctor identity (`doctor_id` scoping) and must avoid exposing global multi-tenant resources.