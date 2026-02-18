(function () {
    window.FieldComponents = window.FieldComponents || {};

    // базовый безопасный вывод
    function escapeHtml(v) {
        if (v === null || v === undefined) return '';
        return String(v)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // на всякий — нормализуем режим
    function normalizeMode(mode) {
        const m = String(mode || '').toLowerCase();
        if (m === 'form') return 'edit'; // совместимость со старым вызовом
        if (m === 'create' || m === 'edit' || m === 'show') return m;
        return 'show';
    }

    function isEditableMode(mode) {
        const m = normalizeMode(mode);
        return m === 'create' || m === 'edit';
    }

    window.FieldComponents.text = {

        // ===== TABLE (mode=index) =====
        index({ value }) {
            if (value === null || value === undefined || value === '') return '—';
            return escapeHtml(value);
        },

        // ===== SHOW (mode=show) =====
        show({ value, config ={} }) {
            if ((config.modifier != null) && (config.modifier == 'flag')) {
                return '<img src=\'assets/images/flags/'+value+'.svg\' >';
            }
            if (value === null || value === undefined || value === '') return '—';
            return escapeHtml(value);
        },

        // ===== EDIT (mode=edit) =====
        edit({ name, value, disabled = false, required = false, placeholder = '', config ={} }) {
            const v = (value === null || value === undefined) ? '' : String(value);

            return `
                <input
                    type="text"
                    name="${escapeHtml(name)}"
                    value="${escapeHtml(v)}"
                    ${disabled ? 'disabled' : ''}
                    ${required ? 'required' : ''}
                    placeholder="${escapeHtml(placeholder)}"
                    class="form-input w-full"
                >
            `;
        },

        // ===== CREATE (mode=create) =====
        create({ name, value, disabled = false, required = false, placeholder = '', config ={} }) {
            // create идентичен edit — оставляем явно, чтобы не было "mode=form"
            return this.edit({ name, value, disabled, required, placeholder });
        },
        // опционально: универсальный рендер, если где-то удобно вызывать одним методом
        render({ mode, ...props }) {
            //console.log('props',props);
            const m = normalizeMode(mode);
            if (m === 'show') return this.show(props);
            if (isEditableMode(m)) return (m === 'create') ? this.create(props) : this.edit(props);
            return this.show(props);
        }
    };
})();
