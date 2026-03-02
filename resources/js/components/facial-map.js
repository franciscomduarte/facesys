export default function facialMap({ initialPoints = [], editable = false }) {
    return {
        points: initialPoints.map(p => ({
            regiao_musculo: p.regiao_musculo || '',
            unidades_aplicadas: p.unidades_aplicadas || '',
            observacoes: p.observacoes || '',
            coord_x: parseFloat(p.coord_x),
            coord_y: parseFloat(p.coord_y),
        })),
        editable,
        showPointForm: false,
        editingExisting: false,
        selectedIndex: null,
        currentPoint: {
            regiao_musculo: '',
            unidades_aplicadas: '',
            observacoes: '',
            coord_x: 0,
            coord_y: 0,
        },

        handleSvgClick(event) {
            const svg = this.$refs.svgFace;
            const pt = svg.createSVGPoint();
            pt.x = event.clientX;
            pt.y = event.clientY;
            const svgPoint = pt.matrixTransform(svg.getScreenCTM().inverse());

            // Converter de coordenadas SVG (0-400, 0-500) para percentual (0-100)
            const coordX = (svgPoint.x / 400) * 100;
            const coordY = (svgPoint.y / 500) * 100;

            // Auto-detectar regiao
            const region = this.detectRegion(coordX, coordY);

            this.currentPoint = {
                regiao_musculo: region,
                unidades_aplicadas: '',
                observacoes: '',
                coord_x: Math.round(coordX * 10000) / 10000,
                coord_y: Math.round(coordY * 10000) / 10000,
            };
            this.editingExisting = false;
            this.selectedIndex = null;
            this.showPointForm = true;
        },

        detectRegion(x, y) {
            if (y < 28 && x > 20 && x < 80) return 'Frontalis';
            if (y >= 26 && y < 34 && x > 42 && x < 58) return 'Procerus';
            if (y >= 26 && y < 34 && x > 30 && x <= 42) return 'Corrugador Esquerdo';
            if (y >= 26 && y < 34 && x >= 58 && x < 70) return 'Corrugador Direito';
            if (y >= 30 && y < 42 && x > 18 && x < 38) return 'Orbicularis Oculi E';
            if (y >= 30 && y < 42 && x > 62 && x < 82) return 'Orbicularis Oculi D';
            if (y >= 56 && y < 70 && x > 36 && x < 64) return 'Orbicularis Oris';
            if (y >= 48 && y < 72 && x > 12 && x < 33) return 'Masseter E';
            if (y >= 48 && y < 72 && x > 67 && x < 88) return 'Masseter D';
            if (y >= 70 && y < 82 && x > 38 && x < 62) return 'Mentual';
            return '';
        },

        selectPoint(index) {
            if (!this.editable) return;
            this.selectedIndex = index;
            this.editPoint(index);
        },

        editPoint(index) {
            this.currentPoint = { ...this.points[index] };
            this.selectedIndex = index;
            this.editingExisting = true;
            this.showPointForm = true;
        },

        savePoint() {
            if (!this.currentPoint.regiao_musculo || !this.currentPoint.unidades_aplicadas) {
                return;
            }

            if (this.editingExisting && this.selectedIndex !== null) {
                this.points[this.selectedIndex] = { ...this.currentPoint };
            } else {
                this.points.push({ ...this.currentPoint });
            }

            this.cancelPoint();
        },

        removePoint() {
            if (this.selectedIndex !== null) {
                this.points.splice(this.selectedIndex, 1);
            }
            this.cancelPoint();
        },

        removePointAt(index) {
            this.points.splice(index, 1);
            if (this.selectedIndex === index) {
                this.cancelPoint();
            }
        },

        cancelPoint() {
            this.showPointForm = false;
            this.editingExisting = false;
            this.selectedIndex = null;
            this.currentPoint = {
                regiao_musculo: '',
                unidades_aplicadas: '',
                observacoes: '',
                coord_x: 0,
                coord_y: 0,
            };
        },

        totalUnits() {
            return this.points.reduce((sum, p) => sum + parseFloat(p.unidades_aplicadas || 0), 0);
        },
    };
}
