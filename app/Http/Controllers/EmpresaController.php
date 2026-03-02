<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Empresa::class);

        $query = Empresa::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome_fantasia', 'ilike', "%{$search}%")
                  ->orWhere('razao_social', 'ilike', "%{$search}%")
                  ->orWhere('cnpj', 'ilike', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $empresas = $query->orderBy('nome_fantasia')->paginate(15)->withQueryString();

        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        $this->authorize('create', Empresa::class);

        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Empresa::class);

        $data = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:empresas,cnpj',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'status' => 'required|in:ativa,inativa,suspensa',
        ]);

        $empresa = Empresa::create($data);

        return redirect()
            ->route('empresas.show', $empresa)
            ->with('success', 'Empresa cadastrada com sucesso.');
    }

    public function show(Empresa $empresa)
    {
        $this->authorize('view', $empresa);

        $empresa->loadCount(['users', 'patients', 'procedimentos', 'agendamentos']);

        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        $this->authorize('update', $empresa);

        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $this->authorize('update', $empresa);

        $data = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:empresas,cnpj,' . $empresa->id,
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'status' => 'required|in:ativa,inativa,suspensa',
        ]);

        $empresa->update($data);

        return redirect()
            ->route('empresas.show', $empresa)
            ->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Empresa $empresa)
    {
        $this->authorize('delete', $empresa);

        $empresa->delete();

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa removida com sucesso.');
    }

    public function toggleStatus(Empresa $empresa)
    {
        $this->authorize('toggleStatus', $empresa);

        $novoStatus = match ($empresa->status) {
            'ativa' => 'suspensa',
            'suspensa' => 'ativa',
            'inativa' => 'ativa',
        };

        $empresa->update(['status' => $novoStatus]);

        return redirect()
            ->back()
            ->with('success', "Empresa alterada para '{$novoStatus}' com sucesso.");
    }
}
