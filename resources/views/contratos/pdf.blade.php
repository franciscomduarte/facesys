<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .page {
            padding: 30px 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 20px;
            color: #4f46e5;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .contract-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #4f46e5;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-grid {
            width: 100%;
        }
        .info-grid td {
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .info-value {
            font-size: 12px;
            color: #333;
        }
        .contract-body {
            margin-top: 15px;
            text-align: justify;
            font-size: 11px;
            line-height: 1.7;
        }
        .contract-body p {
            margin-bottom: 8px;
        }
        .contract-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .contract-body table th {
            background-color: #f3f4f6;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
        }
        .contract-body table td {
            padding: 6px;
            font-size: 11px;
            border-bottom: 1px solid #e5e7eb;
        }
        .signatures-grid {
            width: 100%;
            margin-top: 40px;
        }
        .signatures-grid td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 10px 20px;
        }
        .signature-img {
            max-height: 60px;
            margin: 0 auto 5px;
        }
        .signature-name {
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }
        .signature-detail {
            font-size: 9px;
            color: #666;
        }
        .signature-area {
            text-align: center;
            margin-top: 50px;
        }
        .signature-line {
            width: 250px;
            border-top: 1px solid #333;
            margin: 0 auto;
            padding-top: 5px;
            font-size: 11px;
        }
        .verification-box {
            margin-top: 20px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .verification-hash {
            font-family: monospace;
            font-size: 8px;
            word-break: break-all;
            color: #333;
            margin-top: 3px;
        }
        .legal-notice {
            margin-top: 30px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Cabecalho --}}
        <div class="header">
            <h1>LC Estetica</h1>
            <p>Clinica de Estetica Avancada</p>
        </div>

        <div class="contract-title">Contrato de Prestacao de Servicos</div>

        {{-- Dados do Contrato --}}
        <div class="section">
            <div class="section-title">Identificacao</div>
            <table class="info-grid">
                <tr>
                    <td>
                        <span class="info-label">Paciente:</span><br>
                        <span class="info-value">{{ $contrato->patient->nome_completo }}</span>
                    </td>
                    <td>
                        <span class="info-label">CPF:</span><br>
                        <span class="info-value">{{ $contrato->patient->cpf }}</span>
                    </td>
                    <td>
                        <span class="info-label">Data de Nascimento:</span><br>
                        <span class="info-value">{{ $contrato->patient->data_nascimento->format('d/m/Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Profissional:</span><br>
                        <span class="info-value">{{ $contrato->profissional->name ?? $contrato->treatmentSession->profissional_responsavel }}</span>
                    </td>
                    <td>
                        <span class="info-label">Data do Atendimento:</span><br>
                        <span class="info-value">{{ $contrato->treatmentSession->data_sessao->format('d/m/Y') }}</span>
                    </td>
                    @if($contrato->valor_total)
                    <td>
                        <span class="info-label">Valor Total:</span><br>
                        <span class="info-value">R$ {{ number_format($contrato->valor_total, 2, ',', '.') }}</span>
                    </td>
                    @endif
                </tr>
            </table>
        </div>

        {{-- Conteudo do Contrato --}}
        <div class="section">
            <div class="contract-body">
                {!! $contrato->conteudo_renderizado !!}
            </div>
        </div>

        {{-- Assinatura --}}
        @if(isset($documentoAssinavel) && $documentoAssinavel && $documentoAssinavel->isFinalizado())
            <table class="signatures-grid">
                <tr>
                    <td>
                        @if($documentoAssinavel->assinaturaPaciente && $documentoAssinavel->assinaturaPaciente->assinatura_imagem)
                            <img src="{{ storage_path('app/' . $documentoAssinavel->assinaturaPaciente->assinatura_imagem) }}" class="signature-img">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                        <div style="border-top: 1px solid #333; padding-top: 5px;">
                            <div class="signature-name">{{ $documentoAssinavel->assinaturaPaciente->nome_assinante ?? 'Paciente' }}</div>
                            <div class="signature-detail">
                                CPF: ***.***{{ substr($documentoAssinavel->assinaturaPaciente->documento_assinante ?? '', 6, 3) }}-**
                            </div>
                            <div class="signature-detail">
                                {{ $documentoAssinavel->assinaturaPaciente?->data_assinatura?->format('d/m/Y H:i') }}
                            </div>
                            <div class="signature-detail">Paciente / Contratante</div>
                        </div>
                    </td>
                    <td>
                        @if($documentoAssinavel->assinaturaProfissional && $documentoAssinavel->assinaturaProfissional->assinatura_imagem)
                            <img src="{{ storage_path('app/' . $documentoAssinavel->assinaturaProfissional->assinatura_imagem) }}" class="signature-img">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                        <div style="border-top: 1px solid #333; padding-top: 5px;">
                            <div class="signature-name">{{ $documentoAssinavel->assinaturaProfissional->nome_assinante ?? 'Profissional' }}</div>
                            <div class="signature-detail">
                                CPF: ***.***{{ substr($documentoAssinavel->assinaturaProfissional->documento_assinante ?? '', 6, 3) }}-**
                            </div>
                            <div class="signature-detail">
                                {{ $documentoAssinavel->assinaturaProfissional?->data_assinatura?->format('d/m/Y H:i') }}
                            </div>
                            <div class="signature-detail">Profissional Responsavel / Contratada</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="verification-box">
                <p>Documento assinado eletronicamente — Verifique a autenticidade em:</p>
                <p>{{ route('assinatura.verificar.hash', $documentoAssinavel->hash_documento) }}</p>
                <div class="verification-hash">Hash: {{ $documentoAssinavel->hash_documento }}</div>
            </div>
        @else
            <table class="signatures-grid">
                <tr>
                    <td>
                        <div style="height: 60px;"></div>
                        <div class="signature-line" style="width: 100%;">
                            {{ $contrato->patient->nome_completo }}<br>
                            <span style="font-size: 9px; color: #999;">Paciente / Contratante</span>
                        </div>
                    </td>
                    <td>
                        <div style="height: 60px;"></div>
                        <div class="signature-line" style="width: 100%;">
                            {{ $contrato->profissional->name ?? $contrato->treatmentSession->profissional_responsavel }}<br>
                            <span style="font-size: 9px; color: #999;">Profissional Responsavel / Contratada</span>
                        </div>
                    </td>
                </tr>
            </table>
        @endif

        {{-- Aviso Legal --}}
        <div class="legal-notice">
            <p>Este documento foi gerado eletronicamente pelo sistema LC Estetica.</p>
            <p>Contrato #{{ $contrato->id }} — Gerado em {{ $contrato->data_geracao ? $contrato->data_geracao->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
