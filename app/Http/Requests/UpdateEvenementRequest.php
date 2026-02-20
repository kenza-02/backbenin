<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvenementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => 'sometimes|required|string|max:245',
            'description' => 'sometimes|required|string',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after_or_equal:date_debut',
            'heure_debut' => 'sometimes|required|date_format:H:i',
            'heure_fin' => 'sometimes|required|date_format:H:i',
            'type' => 'sometimes|required|string|max:245',
            'lieu' => 'nullable|string|max:245',
            'lien' => 'nullable|string|max:245',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire',
            'description.required' => 'La description est obligatoire',
            'date_debut.required' => 'La date de début est obligatoire',
            'date_fin.required' => 'La date de fin est obligatoire',
            'date_fin.after_or_equal' => 'La date de fin doit être après la date de début',
            'heure_debut.required' => 'L\'heure de début est obligatoire',
            'heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM',
            'heure_fin.required' => 'L\'heure de fin est obligatoire',
            'heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM',
            'type.required' => 'Le type est obligatoire',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF',
            
        ];
    }
}
