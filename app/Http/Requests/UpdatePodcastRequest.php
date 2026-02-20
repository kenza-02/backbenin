<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePodcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'membre_id' => 'sometimes|required|exists:sn_membres,id',
            'categorie_id' => 'sometimes|required|exists:sn_categories,id',
            'fichier' => 'nullable|file|mimes:mp3,wav,m4a,ogg',
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le titre du podcast est obligatoire',
            'membre_id.required' => 'Le membre est obligatoire',
            'membre_id.exists' => 'Le membre sélectionné n\'existe pas',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas',
            'fichier.mimes' => 'Le fichier doit être au format MP3, WAV, M4A ou OGG',
            
        ];
    }
}
