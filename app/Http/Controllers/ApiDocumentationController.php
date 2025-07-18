<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Portail Education API Documentation",
 *      description="Documentation de l'API pour le Portail Education",
 *      @OA\Contact(
 *          email="support@example.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Entrez le jeton Bearer dans ce champ"
 * )
 *
 * @OA\Schema(
 *     schema="UserRequest",
 *     title="User Request",
 *     description="Données de la requête pour la création/mise à jour d'un utilisateur",
 *     @OA\Property(property="first_name", type="string", example="John", description="Prénom de l'utilisateur"),
 *     @OA\Property(property="last_name", type="string", example="Doe", description="Nom de famille de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Adresse email de l'utilisateur (unique)"),
 *     @OA\Property(property="phone", type="string", example="+1234567890", description="Numéro de téléphone de l'utilisateur (unique)"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="2000-01-01", description="Date de naissance de l'utilisateur"),
 *     @OA\Property(property="gender", type="string", example="Male", description="Sexe de l'utilisateur"),
 *     @OA\Property(property="address", type="string", example="123 Main St", description="Adresse de l'utilisateur"),
 *     @OA\Property(property="role_id", type="integer", example=1, description="ID du rôle de l'utilisateur (doit exister dans la table des rôles)")
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Modèle d'utilisateur",
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1, description="ID de l'utilisateur"),
 *     @OA\Property(property="first_name", type="string", example="John", description="Prénom de l'utilisateur"),
 *     @OA\Property(property="last_name", type="string", example="Doe", description="Nom de famille de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Adresse email de l'utilisateur"),
 *     @OA\Property(property="phone", type="string", example="+1234567890", description="Numéro de téléphone de l'utilisateur"),
 *     @OA\Property(property="role_id", type="integer", example=1, description="ID du rôle de l'utilisateur"),
 *     @OA\Property(property="status", type="integer", example=1, description="Statut de l'utilisateur"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, description="Date de création de l'utilisateur"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, description="Date de dernière mise à jour de l'utilisateur")
 * )
 */
class ApiDocumentationController extends Controller
{
    //
}
