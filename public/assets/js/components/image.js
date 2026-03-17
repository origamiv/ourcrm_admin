(function () {
    window.FieldComponents = window.FieldComponents || {};

    function getUrl(value) {
        if (value === null || value === undefined || value === '') return null;
        let parsed = value;
        if (typeof value === 'string') {
            try { parsed = JSON.parse(value); } catch (e) { return value; }
        }
        if (typeof parsed === 'object' && parsed !== null) {
            return parsed.main ?? Object.values(parsed)[0] ?? null;
        }
        return String(value);
    }

    window.FieldComponents.image = {

        // ===== TABLE (mode=index) =====
        index({ value }) {
            const url = getUrl(value);
            if (!url) return '—';
            return `<img src="${url}" style="height:40px;width:auto;object-fit:cover;border-radius:4px" loading="lazy">`;
        },

        // ===== SHOW (mode=show) =====
        show({ value }) {
            const url = getUrl(value);
            if (!url) return '—';
            return `<img src="${url}" style="max-height:200px;max-width:100%;object-fit:contain;border-radius:8px">`;
        },

        render({ mode, ...props }) {
            if (mode === 'index') return this.index(props);
            return this.show(props);
        }
    };
})();
