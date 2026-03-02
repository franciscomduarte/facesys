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
            line-height: 1.5;
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
        .medicamentos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .medicamentos-table th {
            background-color: #f3f4f6;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            padding: 8px 6px;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
        }
        .medicamentos-table td {
            padding: 8px 6px;
            font-size: 11px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .medicamentos-table tr:last-child td {
            border-bottom: none;
        }
        .obs-item {
            font-size: 10px;
            color: #666;
            font-style: italic;
            margin-top: 2px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
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
        .legal-notice {
            margin-top: 30px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-emitida {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-rascunho {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-assinada {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .signatures-grid {
            width: 100%;
            margin-top: 30px;
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
    </style>
</head>
<body>
    <div class="page">
        {{-- Cabecalho --}}
        <div class="header">
            <h1>LC Estetica</h1>
            <p>Clinica de Estetica Avancada</p>
            <p>Prescricao Medica</p>
        </div>

        {{-- Dados do Paciente --}}
        <div class="section">
            <div class="section-title">Dados do Paciente</div>
            <table class="info-grid">
                <tr>
                    <td>
                        <span class="info-label">Nome:</span><br>
                        <span class="info-value">{{ $prescricao->patient->nome_completo }}</span>
                    </td>
                    <td>
                        <span class="info-label">CPF:</span><br>
                        <span class="info-value">{{ $prescricao->patient->cpf }}</span>
                    </td>
                    <td>
                        <span class="info-label">Data de Nascimento:</span><br>
                        <span class="info-value">{{ $prescricao->patient->data_nascimento->format('d/m/Y') }}</span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Dados da Prescricao --}}
        <div class="section">
            <div class="section-title">Prescricao</div>
            <table class="info-grid">
                <tr>
                    <td>
                        <span class="info-label">Profissional:</span><br>
                        <span class="info-value">{{ $prescricao->profissional_responsavel }}</span>
                    </td>
                    <td>
                        <span class="info-label">Data de Emissao:</span><br>
                        <span class="info-value">{{ $prescricao->data_emissao ? $prescricao->data_emissao->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <span class="info-label">Sessao:</span><br>
                        <span class="info-value">{{ $prescricao->treatmentSession->data_sessao->format('d/m/Y') }}</span>
                    </td>
                </tr>
            </table>
            @if($prescricao->observacoes_gerais)
                <div style="margin-top: 8px;">
                    <span class="info-label">Observacoes:</span><br>
                    <span class="info-value">{{ $prescricao->observacoes_gerais }}</span>
                </div>
            @endif
        </div>

        {{-- Medicamentos --}}
        <div class="section">
            <div class="section-title">Medicamentos Prescritos</div>
            <table class="medicamentos-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Medicamento</th>
                        <th style="width: 15%;">Dosagem</th>
                        <th style="width: 12%;">Via</th>
                        <th style="width: 18%;">Frequencia</th>
                        <th style="width: 15%;">Duracao</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescricao->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item->medicamento }}
                                @if($item->observacoes)
                                    <div class="obs-item">{{ $item->observacoes }}</div>
                                @endif
                            </td>
                            <td>{{ $item->dosagem }}</td>
                            <td>{{ $item->via_administracao }}</td>
                            <td>{{ $item->frequencia }}</td>
                            <td>{{ $item->duracao }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                            <div class="signature-detail">Paciente</div>
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
                            <div class="signature-detail">Profissional Responsavel</div>
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
            <div class="signature-area">
                <div class="signature-line">
                    {{ $prescricao->profissional_responsavel }}<br>
                    <span style="font-size: 9px; color: #999;">Assinatura do Profissional</span>
                </div>
            </div>
        @endif

        {{-- Aviso Legal --}}
        <div class="legal-notice">
            <p>Este documento foi gerado eletronicamente pelo sistema LC Estetica.</p>
            <p>Prescricao #{{ $prescricao->id }} — Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
