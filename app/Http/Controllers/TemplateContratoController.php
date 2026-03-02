<?php

namespace App\Http\Controllers;

use App\Models\TemplateContrato;
use App\Http\Requests\StoreTemplateContratoRequest;
use App\Http\Requests\UpdateTemplateContratoRequest;

class TemplateContratoController extends Controller
{
    public function index()
    {
        $templates = TemplateContrato::orderBy('nome')->get();

        return view('templates-contrato.index', compact('templates'));
    }

    public function create()
    {
        return view('templates-contrato.create');
    }

    public function store(StoreTemplateContratoRequest $request)
    {
        $validated = $request->validated();

        $template = TemplateContrato::create($validated);

        return redirect()
            ->route('templates-contrato.show', $template)
            ->with('success', 'Template criado com sucesso.');
    }

    public function show(TemplateContrato $templates_contrato)
    {
        return view('templates-contrato.show', ['template' => $templates_contrato]);
    }

    public function edit(TemplateContrato $templates_contrato)
    {
        return view('templates-contrato.edit', ['template' => $templates_contrato]);
    }

    public function update(UpdateTemplateContratoRequest $request, TemplateContrato $templates_contrato)
    {
        $templates_contrato->update($request->validated());

        return redirect()
            ->route('templates-contrato.show', $templates_contrato)
            ->with('success', 'Template atualizado com sucesso.');
    }

    public function destroy(TemplateContrato $templates_contrato)
    {
        $this->authorize('delete', $templates_contrato);

        $templates_contrato->delete();

        return redirect()
            ->route('templates-contrato.index')
            ->with('success', 'Template removido com sucesso.');
    }

    public function toggleAtivo(TemplateContrato $templates_contrato)
    {
        $this->authorize('update', $templates_contrato);

        $templates_contrato->update(['ativo' => !$templates_contrato->ativo]);

        return back()->with('success', 'Status do template atualizado.');
    }
}
