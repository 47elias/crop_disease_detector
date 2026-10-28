<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'crop' => $this->crop?->name,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'symptoms' => $this->symptoms,
            'is_healthy_class' => $this->is_healthy_class,
            'model_class_index' => $this->model_class_index,
            'severity_level' => $this->severity_level,
            'treatment_guidelines' => $this->treatmentGuidelines->map(fn ($guideline): array => [
                'language' => $guideline->language,
                'title' => $guideline->title,
                'steps' => $guideline->steps,
                'chemical_options' => $guideline->chemical_options,
                'cultural_practices' => $guideline->cultural_practices,
                'sms_text' => $guideline->sms_text,
            ]),
        ];
    }
}
