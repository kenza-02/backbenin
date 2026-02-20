<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:sn_categories,id',
            'fichier' => 'required|file|mimes:pdf,doc,docx,txt', // 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé du document est obligatoire',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas',
            'fichier.required' => 'Le fichier est obligatoire',
            'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX ou TXT',
           
        ];
    }
}
