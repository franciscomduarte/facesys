@props(['session', 'patient', 'procedimentos'])

@if($procedimentos->count())
<div x-data="{
    showForm: false,
    previews: [],
    handleFiles(event) {
        this.previews = [];
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previews.push({ name: files[i].name, src: e.target.result });
            };
            reader.readAsDataURL(files[i]);
        }
    },
    clearPreviews() {
        this.previews = [];
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
        }
    }
}" class="mt-6 border-t border-gray-200 pt-4">
    <div class="flex items-center justify-between mb-3">
        <h5 class="text-sm font-medium text-gray-700">Adicionar Fotos</h5>
        <button type="button" @click="showForm = !showForm; if(!showForm) clearPreviews()"
                class="text-sm text-indigo-600 hover:text-indigo-800">
            <span x-text="showForm ? 'Cancelar' : '+ Nova Foto'"></span>
        </button>
    </div>

    <div x-show="showForm" x-cloak x-transition class="bg-gray-50 rounded-lg p-4">
        <form method="POST"
              action="{{ route('patients.sessions.fotos.store', [$patient, $session]) }}"
              enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="foto_procedimento_id" value="Procedimento *" />
                    <select id="foto_procedimento_id" name="procedimento_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Selecione...</option>
                        @foreach($procedimentos as $proc)
                            <option value="{{ $proc->id }}">{{ $proc->nome }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('procedimento_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="foto_tipo" value="Tipo *" />
                    <select id="foto_tipo" name="tipo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="antes" {{ old('tipo') == 'antes' ? 'selected' : '' }}>Antes</option>
                        <option value="depois" {{ old('tipo') == 'depois' ? 'selected' : '' }}>Depois</option>
                    </select>
                    <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="foto_data_registro" value="Data do Registro *" />
                    <x-text-input id="foto_data_registro" name="data_registro" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('data_registro', $session->data_sessao->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('data_registro')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="foto_profissional" value="Profissional *" />
                    <x-text-input id="foto_profissional" name="profissional_responsavel" type="text"
                                  class="mt-1 block w-full"
                                  :value="old('profissional_responsavel', $session->profissional_responsavel)" required />
                    <x-input-error :messages="$errors->get('profissional_responsavel')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="foto_regiao" value="Regiao Facial" />
                    <x-text-input id="foto_regiao" name="regiao_facial" type="text"
                                  class="mt-1 block w-full"
                                  :value="old('regiao_facial')"
                                  placeholder="Ex: Testa, Periorbicular..." />
                    <x-input-error :messages="$errors->get('regiao_facial')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="foto_observacoes" value="Observacoes" />
                    <x-text-input id="foto_observacoes" name="observacoes" type="text"
                                  class="mt-1 block w-full"
                                  :value="old('observacoes')"
                                  placeholder="Observacoes clinicas..." />
                    <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="foto_arquivos" value="Fotos * (JPG, PNG, WebP — max 10MB cada)" />
                    <input type="file" id="foto_arquivos" name="fotos[]" multiple accept="image/jpeg,image/png,image/webp"
                           x-ref="fileInput"
                           @change="handleFiles($event)"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                    <x-input-error :messages="$errors->get('fotos')" class="mt-2" />
                    <x-input-error :messages="$errors->get('fotos.*')" class="mt-2" />
                </div>
            </div>

            {{-- Preview --}}
            <div x-show="previews.length > 0" class="mt-4">
                <p class="text-xs text-gray-500 mb-2">Preview (<span x-text="previews.length"></span> arquivo(s)):</p>
                <div class="flex flex-wrap gap-3">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="relative">
                            <img :src="preview.src" :alt="preview.name"
                                 class="h-20 w-20 object-cover rounded-lg border border-gray-200 shadow-sm" />
                            <p class="text-xs text-gray-400 mt-1 truncate max-w-[80px]" x-text="preview.name"></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>Enviar Foto(s)</x-primary-button>
            </div>
        </form>
    </div>
</div>
@endif
