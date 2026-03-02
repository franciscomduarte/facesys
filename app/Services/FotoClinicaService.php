<?php

namespace App\Services;

use App\Models\FotoClinica;
use App\Models\Patient;
use App\Models\TreatmentSession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FotoClinicaService
{
    public function upload(TreatmentSession $session, UploadedFile $file, array $data): FotoClinica
    {
        return DB::transaction(function () use ($session, $file, $data) {
            $uuid = Str::uuid();
            $extension = $file->getClientOriginalExtension();
            $filename = "{$uuid}.{$extension}";
            $directory = "fotos_clinicas/paciente_{$session->patient_id}/sessao_{$session->id}";

            Storage::disk('local')->putFileAs($directory, $file, $filename);

            return FotoClinica::create([
                'patient_id' => $session->patient_id,
                'treatment_session_id' => $session->id,
                'procedimento_id' => $data['procedimento_id'],
                'tipo' => $data['tipo'],
                'caminho_arquivo' => "{$directory}/{$filename}",
                'nome_original' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'tamanho_bytes' => $file->getSize(),
                'data_registro' => $data['data_registro'],
                'profissional_responsavel' => $data['profissional_responsavel'],
                'observacoes' => $data['observacoes'] ?? null,
                'regiao_facial' => $data['regiao_facial'] ?? null,
                'ordem' => $data['ordem'] ?? 0,
            ]);
        });
    }

    public function uploadMultiple(TreatmentSession $session, array $files, array $data): Collection
    {
        $photos = collect();

        foreach ($files as $index => $file) {
            $photoData = array_merge($data, ['ordem' => $index]);
            $photos->push($this->upload($session, $file, $photoData));
        }

        return $photos;
    }

    public function delete(FotoClinica $foto): bool
    {
        return $foto->delete();
    }

    public function forceDelete(FotoClinica $foto): bool
    {
        Storage::disk('local')->delete($foto->caminho_arquivo);

        return $foto->forceDelete();
    }

    public function getForSession(TreatmentSession $session): Collection
    {
        return $session->clinicalPhotos()
            ->with('procedimento')
            ->orderBy('procedimento_id')
            ->orderBy('tipo')
            ->orderBy('ordem')
            ->get();
    }

    public function getForPatient(Patient $patient): Collection
    {
        return $patient->clinicalPhotos()
            ->with(['treatmentSession', 'procedimento'])
            ->orderByDesc('data_registro')
            ->orderBy('procedimento_id')
            ->orderBy('tipo')
            ->get();
    }

    public function getComparisonByProcedimento(Patient $patient, int $procedimentoId): array
    {
        $photos = $patient->clinicalPhotos()
            ->where('procedimento_id', $procedimentoId)
            ->orderBy('data_registro')
            ->orderBy('ordem')
            ->get();

        return [
            'antes' => $photos->where('tipo', 'antes')->values(),
            'depois' => $photos->where('tipo', 'depois')->values(),
        ];
    }
}
