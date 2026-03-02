<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\Profile\DoctorProfilePasswordUpdateRequest;
use App\Http\Requests\Doctor\Profile\DoctorProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DoctorProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('doctor');

        return response()->json([
            'data' => [
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'specialization' => $user->doctor?->specialization,
            ],
        ]);
    }

    public function update(DoctorProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user()->load('doctor');
        $validated = $request->validated();

        $userData = array_filter(
            [
                'name' => $validated['name'] ?? null,
                'lastname' => $validated['lastname'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ],
            fn($value) => $value !== null
        );

        if (!empty($userData)) {
            $user->update($userData);
        }

        if (array_key_exists('specialization', $validated) && $user->doctor) {
            $user->doctor->update([
                'specialization' => $validated['specialization'],
            ]);
        }

        $user->refresh()->load('doctor');

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'data' => [
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'specialization' => $user->doctor?->specialization,
            ],
        ]);
    }

    public function updatePassword(DoctorProfilePasswordUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual no es correcta.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }
}
