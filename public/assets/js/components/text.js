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

    window.FieldComponents.text = {

        // ===== TABLE (mode=index) =====
        index({ value }) {
            if (value === null || value === undefined || value === '') return '—';
            return 'HHH '+escapeHtml(value);
        },

        // ===== FORM (mode=form) =====
        form({ name, value, disabled = false, required = false, placeholder = '' }) {
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
        }
    };
})();
