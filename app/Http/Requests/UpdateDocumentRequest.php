<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
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
            'categorie_id' => 'sometimes|required|exists:sn_categories,id',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,txt',
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé du document est obligatoire',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas',
            'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX ou TXT',
           
        ];
    }
}
