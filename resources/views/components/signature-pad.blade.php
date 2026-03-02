@props(['name' => 'assinatura_imagem_base64', 'label' => 'Assinatura'])

<div x-data="signaturePad()" class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>

    <div class="border-2 rounded-lg overflow-hidden" :class="signed ? 'border-green-400' : 'border-gray-300'">
        <canvas
            x-ref="canvas"
            @mousedown="startDrawing($event)"
            @mousemove="draw($event)"
            @mouseup="stopDrawing()"
            @mouseleave="stopDrawing()"
            @touchstart.prevent="startDrawing($event)"
            @touchmove.prevent="draw($event)"
            @touchend.prevent="stopDrawing()"
            class="w-full bg-white cursor-crosshair touch-none"
            style="height: 200px;"
        ></canvas>
    </div>

    <div class="flex items-center justify-between">
        <button
            type="button"
            @click="clear()"
            class="text-sm text-gray-500 hover:text-gray-700 underline"
        >
            Limpar assinatura
        </button>
        <span x-show="signed" class="text-sm text-green-600 font-medium">
            Assinatura registrada
        </span>
    </div>

    <input type="hidden" :name="'{{ $name }}'" x-ref="input" :value="dataUrl">
</div>

@once
@push('scripts')
<script>
function signaturePad() {
    return {
        drawing: false,
        signed: false,
        dataUrl: '',
        ctx: null,
        lastX: 0,
        lastY: 0,

        init() {
            const canvas = this.$refs.canvas;
            this.ctx = canvas.getContext('2d');
            this.resizeCanvas();
            window.addEventListener('resize', () => this.resizeCanvas());
        },

        resizeCanvas() {
            const canvas = this.$refs.canvas;
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            this.ctx.scale(dpr, dpr);
            this.ctx.strokeStyle = '#1a1a1a';
            this.ctx.lineWidth = 2;
            this.ctx.lineCap = 'round';
            this.ctx.lineJoin = 'round';
        },

        getCoords(e) {
            const canvas = this.$refs.canvas;
            const rect = canvas.getBoundingClientRect();
            if (e.touches && e.touches.length > 0) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top
                };
            }
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        },

        startDrawing(e) {
            this.drawing = true;
            const coords = this.getCoords(e);
            this.lastX = coords.x;
            this.lastY = coords.y;
            this.ctx.beginPath();
            this.ctx.moveTo(coords.x, coords.y);
        },

        draw(e) {
            if (!this.drawing) return;
            const coords = this.getCoords(e);
            this.ctx.beginPath();
            this.ctx.moveTo(this.lastX, this.lastY);
            this.ctx.lineTo(coords.x, coords.y);
            this.ctx.stroke();
            this.lastX = coords.x;
            this.lastY = coords.y;
        },

        stopDrawing() {
            if (!this.drawing) return;
            this.drawing = false;
            this.signed = true;
            this.dataUrl = this.$refs.canvas.toDataURL('image/png');
            this.$refs.input.value = this.dataUrl;
        },

        clear() {
            const canvas = this.$refs.canvas;
            this.ctx.clearRect(0, 0, canvas.width, canvas.height);
            this.signed = false;
            this.dataUrl = '';
            this.$refs.input.value = '';
        }
    };
}
</script>
@endpush
@endonce
