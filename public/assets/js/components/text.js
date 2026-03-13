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
        index(props) {
            const {value, config = {}, row = {}} = props;
            if (value === null || value === undefined || value === '') return '—';

            // Обработка модификатора link
            if (config.modifier === 'link') {
                let href = config.template ? config.template.replace(/{(\w+)}/g, (_, f) => row[f] ?? '') : value;
                return `<a href="${escapeHtml(href)}" class="text-primary hover:underline">${escapeHtml(value)}</a>`;
            }

            // Обработка модификатора copy
            if (config.modifier === 'copy') {
                return `могло
                    <div class="flex items-center gap-2 group">
                        <span>${escapeHtml(value)}</span>
                        <button type="button" class="text-gray-400 hover:text-primary transition-colors" onclick="FieldComponents.text.copyToClipboard(this, '${escapeHtml(value)}')">
                            <i class="uil uil-copy text-base"></i>
                        </button>
                    </div>
                `;
            }

            return escapeHtml(value);
        },

        // ===== SHOW (mode=show) =====
        show(props) {
            const {entity, name, value, config = {}, row = {}} = props;
            if (value === null || value === undefined || value === '') return '—';

            let modifierFieldValue = value;
            if (config.modifier_field != null && row[config.modifier_field] != null) {
                modifierFieldValue = row[config.modifier_field];
            }

            if (config.modifier === 'flag') {
                return `<img src="/download/image/${entity}/${row['id']}/${name}/flags" width="24" height="24" title="${escapeHtml(value)}" class="inline-block">`;
            }

            if (config.modifier === 'icon') {
                let width = config.width || "24px";
                let height = config.height || "24px";
                return `<img src="/download/image/${entity}/${row['id']}/${name}/icons" width="${width}" height="${height}" title="${escapeHtml(value)}" class="inline-block">`;
            }

            // Обработка модификатора link
            if (config.modifier === 'link') {
                let href = config.template ? config.template.replace(/{(\w+)}/g, (_, f) => row[f] ?? '') : value;
                return `<a href="${escapeHtml(href)}" class="text-primary hover:underline">${escapeHtml(value)}</a>`;
            }

            // Обработка модификатора copy
            if (config.modifier === 'copy') {
                return `
                    <div class="flex items-center gap-2">
                        <span>${escapeHtml(value)}</span>
                        <button type="button" class="text-gray-400 hover:text-primary transition-colors" onclick="FieldComponents.text.copyToClipboard(this, '${escapeHtml(value)}')">
                            <i class="uil uil-copy text-base"></i>
                        </button>
                    </div>
                `;
            }

            return escapeHtml(value);
        },

        copyToClipboard(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalColor = btn.style.color;
                btn.classList.remove('text-gray-400');
                btn.classList.add('text-success');
                setTimeout(() => {
                    btn.classList.remove('text-success');
                    btn.classList.add('text-gray-400');
                }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        },

        // ===== EDIT (mode=edit) =====
        edit({name, value, disabled = false, required = false, placeholder = '', config = {}, row = {}}) {
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
        create({name, value, disabled = false, required = false, placeholder = '', config = {}}) {
            // create идентичен edit — оставляем явно, чтобы не было "mode=form"
            return this.edit({name, value, disabled, required, placeholder});
        },
        // опционально: универсальный рендер, если где-то удобно вызывать одним методом
        render({mode, ...props}) {
            //console.log('props',props);
            const m = normalizeMode(mode);
            if (m === 'show') return this.show(props);
            if (isEditableMode(m)) return (m === 'create') ? this.create(props) : this.edit(props);
            return this.show(props);
        }
    };
})();
