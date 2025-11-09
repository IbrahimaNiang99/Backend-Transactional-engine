<?php

namespace App\Http\Controllers;

use App\Jobs\SendPhoneVerificationSms;
use App\Jobs\CreateUserAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscrire un nouvel utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "phone", "cni", "password", "password_confirmation"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="221771234567"),
     *             @OA\Property(property="cni", type="string", example="1234567890123"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur enregistré avec succès.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully. A verification code has been sent to your phone."),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur."
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|unique:users|max:255',
                'cni' => 'required|string|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'cni' => $request->cni,
                'password' => Hash::make($request->password),
            ]);

        // Dispatch jobs
        SendPhoneVerificationSms::dispatch($user);
        CreateUserAccount::dispatch($user);

        return response()->json([
            'message' => 'User registered successfully. A verification code has been sent to your phone.',
            'user' => $user
        ], 201);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());
            return response()->json(['error' => 'User registration failed'], 500);
        }
    }

    /**
     * Verify the user's phone number.
     * @OA\Post(
     *     path="/api/verify-phone",
     *     summary="Vérifier le numéro de téléphone d'un utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "code"},
     *             @OA\Property(property="phone", type="string", description="Numéro de téléphone à vérifier.", example="221771234567"),
     *             @OA\Property(property="code", type="string", description="Code de vérification à 6 chiffres.", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Numéro de téléphone vérifié avec succès.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Phone number verified successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Le numéro est déjà vérifié."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Code invalide ou expiré, ou données de la requête invalides."
     *     )
     * )
     */
    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'code' => 'required|string|min:6|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user->has_verified_phone) {
            return response()->json(['message' => 'Phone number already verified.'], 400);
        }

        if (is_null($user->phone_verification_code) || $user->phone_verification_code !== $request->code) {
            return response()->json(['error' => 'Invalid verification code.'], 422);
        }

        if (now()->isAfter($user->phone_verification_expires_at)) {
            // Genaration d'un nouveau code et envoi SMS
            SendPhoneVerificationSms::dispatch($user);
            return response()->json(['error' => 'Verification code has expired. A new code has been sent.'], 422);
        }

        $user->update([
            'has_verified_phone' => true,
            'phone_verification_code' => null,
            'phone_verification_expires_at' => null,
        ]);

        return response()->json(['message' => 'Phone number verified successfully.'], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
