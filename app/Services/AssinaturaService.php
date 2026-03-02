<?php

namespace App\Services;

use App\Models\Assinatura;
use App\Models\Contrato;
use App\Models\DocumentoAssinavel;
use App\Models\Prescricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssinaturaService
{
    public function registrarAssinaturaPaciente(
        DocumentoAssinavel $documento,
        array $data,
        Request $request
    ): Assinatura {
        return DB::transaction(function () use ($documento, $data, $request) {
            $imagemPath = null;
            if (!empty($data['assinatura_imagem_base64'])) {
                $imagemPath = $this->storeSignatureImage($data['assinatura_imagem_base64'], $documento);
            }

            $dataAssinatura = now();

            $assinatura = Assinatura::create([
                'documento_assinavel_id' => $documento->id,
                'tipo_assinatura' => 'paciente',
                'nome_assinante' => $data['nome_assinante'],
                'documento_assinante' => $data['documento_assinante'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data_assinatura' => $dataAssinatura,
                'hash_assinatura' => $this->generateSignatureHash(
                    $documento->hash_documento,
                    $data['nome_assinante'],
                    $data['documento_assinante'],
                    $dataAssinatura
                ),
                'assinatura_imagem' => $imagemPath,
            ]);

            $documento->update(['status' => 'assinado_paciente']);

            return $assinatura;
        });
    }

    public function registrarAssinaturaProfissional(
        DocumentoAssinavel $documento,
        array $data,
        Request $request
    ): Assinatura {
        return DB::transaction(function () use ($documento, $data, $request) {
            $imagemPath = null;
            if (!empty($data['assinatura_imagem_base64'])) {
                $imagemPath = $this->storeSignatureImage($data['assinatura_imagem_base64'], $documento);
            }

            $dataAssinatura = now();

            $assinatura = Assinatura::create([
                'documento_assinavel_id' => $documento->id,
                'tipo_assinatura' => 'profissional',
                'nome_assinante' => $data['nome_assinante'],
                'documento_assinante' => $data['documento_assinante'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data_assinatura' => $dataAssinatura,
                'hash_assinatura' => $this->generateSignatureHash(
                    $documento->hash_documento,
                    $data['nome_assinante'],
                    $data['documento_assinante'],
                    $dataAssinatura
                ),
                'assinatura_imagem' => $imagemPath,
            ]);

            $documento->update(['status' => 'finalizado']);

            if ($documento->documento_type === Prescricao::class) {
                $documento->documento->update(['status' => 'assinada']);
            } elseif ($documento->documento_type === Contrato::class) {
                $documento->documento->update(['status' => 'assinado']);
            }

            return $assinatura;
        });
    }

    public function generateSignatureHash(
        string $documentHash,
        string $nome,
        string $cpf,
        $timestamp
    ): string {
        $content = implode('|', [
            $documentHash,
            $nome,
            $cpf,
            $timestamp->toIso8601String(),
        ]);

        return hash('sha256', $content);
    }

    private function storeSignatureImage(string $base64, DocumentoAssinavel $documento): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);

        $directory = config('assinatura.signature_directory', 'assinaturas');
        $filename = Str::uuid() . '.png';
        $path = "{$directory}/documento_{$documento->id}/{$filename}";

        Storage::disk(config('assinatura.signature_disk', 'local'))->put($path, $imageData);

        return $path;
    }
}
