<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\Disease;
use App\Models\MlModelVersion;
use App\Models\TreatmentGuideline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CropDiseaseSeeder extends Seeder
{
    public function run(): void
    {
        $maize = Crop::query()->updateOrCreate(['name' => 'maize'], ['scientific_name' => 'Zea mays']);
        $classes = [
            ['crop' => $maize, 'name' => 'Maize Streak Virus', 'slug' => 'maize-streak-virus', 'description' => 'A viral disease spread by leafhoppers.', 'symptoms' => 'Fine broken chlorotic streaks running parallel to the veins.', 'severity' => 'high', 'index' => 0],
            ['crop' => $maize, 'name' => 'Grey Leaf Spot', 'slug' => 'grey-leaf-spot', 'description' => 'A fungal leaf disease favoured by warm, humid conditions.', 'symptoms' => 'Narrow rectangular grey-tan lesions bounded by leaf veins.', 'severity' => 'moderate', 'index' => 1],
            ['crop' => $maize, 'name' => 'Northern Leaf Blight', 'slug' => 'northern-leaf-blight', 'description' => 'A fungal disease that can reduce maize yield.', 'symptoms' => 'Long cigar-shaped tan lesions along the leaf blade.', 'severity' => 'high', 'index' => 2],
            ['crop' => null, 'name' => 'Healthy', 'slug' => 'healthy', 'description' => 'No visible disease symptoms detected on maize leaves.', 'symptoms' => 'Even green leaf tissue with no clear disease lesions.', 'severity' => 'none', 'index' => 5],
        ];

        foreach ($classes as $class) {
            $disease = Disease::query()->updateOrCreate(
                ['slug' => $class['slug']],
                ['crop_id' => $class['crop']?->id, 'name' => $class['name'], 'description' => $class['description'], 'symptoms' => $class['symptoms'], 'is_healthy_class' => $class['slug'] === 'healthy', 'model_class_index' => $class['index'], 'severity_level' => $class['severity']],
            );

            foreach (['en', 'sn'] as $language) {
                [$title, $steps, $chemical, $practice, $sms] = $this->guideline($class['slug'], $language);
                TreatmentGuideline::query()->updateOrCreate(
                    ['disease_id' => $disease->id, 'language' => $language],
                    ['title' => $title, 'steps' => $steps, 'chemical_options' => $chemical, 'cultural_practices' => $practice, 'sms_text' => Str::limit($sms, 160, '')],
                );
            }
        }

        MlModelVersion::query()->updateOrCreate(
            ['version' => 'mobilenetv3-int8-1.0.0'],
            ['file_path' => 'models/mobilenetv3-int8-1.0.0.tflite', 'size_mb' => 12.4, 'quantisation' => 'int8', 'accuracy' => 0.91, 'precision' => 0.90, 'recall' => 0.89, 'f1_score' => 0.895, 'is_active' => true, 'released_at' => now()],
        );
    }

    /** @return array{string, array<int, string>, string, string, string} */
    private function guideline(string $slug, string $language): array
    {
        $en = [
            'maize-streak-virus' => ['Manage Maize Streak Virus', ['Remove badly affected plants early.', 'Plant resistant varieties and sow on time.', 'Control volunteer maize and grass hosts.'], 'Ask an extension officer before using insecticides.', 'Use resistant seed and keep fields free of volunteer maize.', 'MSV signs found. Remove badly affected plants and ask an extension officer about resistant seed.'],
            'grey-leaf-spot' => ['Manage Grey Leaf Spot', ['Remove or bury infected crop residue.', 'Rotate maize with a non-host crop.', 'Use resistant varieties where available.'], 'Use only locally registered fungicides as directed.', 'Rotate crops and avoid leaving infected maize residue on the surface.', 'Grey Leaf Spot signs found. Rotate crops, manage residue and seek local extension advice.'],
            'northern-leaf-blight' => ['Manage Northern Leaf Blight', ['Plant tolerant varieties.', 'Rotate crops and manage infected residue.', 'Seek advice if lesions spread rapidly.'], 'Use only registered fungicides and follow the label.', 'Rotate away from maize and avoid dense planting.', 'Northern Leaf Blight signs found. Rotate crops and ask extension staff about resistant seed.'],
            'healthy' => ['Keep Plants Healthy', ['Inspect leaves weekly.', 'Use clean seed and maintain good field hygiene.', 'Recheck if new spots or streaks appear.'], 'No chemical treatment is needed for healthy plants.', 'Continue regular scouting and sound crop rotation.', 'No clear disease signs found. Keep scouting and recheck leaves if symptoms appear.'],
        ];
        $sn = [
            'maize-streak-virus' => ['Gadzirai Maize Streak Virus', ['Bvisai zvirimwa zvabatwa zvakanyanya nekukurumidza.', 'Dyara mbeu inoshingirira uye nenguva.', 'Chengetai munda usina chibage chinomera chega nehuswa.'], 'Bvunzai mukuru wezvekurima musati mashandisa mishonga.', 'Shandisai mbeu inoshingirira uye bvisai chibage chinomera chega.', 'Zviratidzo zveMSV zvawanikwa. Bvisai zvirimwa zvanyanya kubatwa uye bvunzai mukuru wezvekurima.'],
            'grey-leaf-spot' => ['Gadzirai Grey Leaf Spot', ['Vigai kana bvisai masarasara ane chirwere.', 'Chinjiranai chibage nechimwe chirimwa.', 'Shandisai mbeu inoshingirira kana iripo.'], 'Shandisai mushonga wakanyoreswa chete muchitevera mirayiridzo.', 'Chinjiranai zvirimwa uye chengetai munda wakachena.', 'Zviratidzo zveGrey Leaf Spot zvawanikwa. Chinjiranai zvirimwa uye bvunzai mukuru wezvekurima.'],
            'northern-leaf-blight' => ['Gadzirai Northern Leaf Blight', ['Dyara mbeu inoshingirira.', 'Chinjiranai zvirimwa uye chengetai masarasara.', 'Kumbirai rubatsiro kana chirwere chichipararira.'], 'Shandisai mushonga wakanyoreswa uye muchitevera chinyorwa.', 'Chinjiranai zvirimwa uye musadyara zvakawandisa.', 'Zviratidzo zveNorthern Leaf Blight zvawanikwa. Chinjiranai zvirimwa uye bvunzai mukuru.'],
            'healthy' => ['Chengetai Zvirimwa Zvine Utano', ['Tarisisai mashizha vhiki rega rega.', 'Shandisai mbeu yakachena uye chengetai munda.', 'Tarisisai zvakare kana zviratidzo zvikaonekwa.'], 'Hapana mushonga unodiwa pazvirimwa zvine utano.', 'Rambai muchitarisa zvirimwa uye muchichinjanisa.', 'Hapana zviratidzo zvakajeka zvawanikwa. Rambai muchitarisa mashizha.'],
        ];

        return ($language === 'sn' ? $sn : $en)[$slug];
    }
}
