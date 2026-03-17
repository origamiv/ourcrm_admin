(function () {
    window.FieldComponents = window.FieldComponents || {};

    // Color palette by numeric index (fallback when no color in config)
    const PALETTE = [
        { bg: '#6b7280', text: '#fff' }, // 0 → gray
        { bg: '#22c55e', text: '#fff' }, // 1 → green
        { bg: '#ef4444', text: '#fff' }, // 2 → red
        { bg: '#f59e0b', text: '#fff' }, // 3 → amber
        { bg: '#3b82f6', text: '#fff' }, // 4 → blue
        { bg: '#8b5cf6', text: '#fff' }, // 5 → purple
        { bg: '#06b6d4', text: '#fff' }, // 6 → cyan
        { bg: '#ec4899', text: '#fff' }, // 7 → pink
    ];

    const DEFAULTS = { '0': 'Неизвестно', '1': 'Активно', '2': 'Выключено', '3': 'В процессе' };

    // Normalize field_items to [{id, name, color?, style?}] regardless of format:
    //   Array format:  [{id, name, color?}, ...]  — used in fakes/user.php etc.
    //   Object format: {'1': 'Label', ...}        — used in simple selects
    function normalizeItems(field_items) {
        if (!field_items) return null;
        if (Array.isArray(field_items)) return field_items;
        // Dict format: values may be strings or objects {name, color, style}
        return Object.entries(field_items).map(([id, val]) => {
            if (val && typeof val === 'object') {
                return { id: String(id), name: String(val.name ?? id), color: val.color, style: val.style };
            }
            return { id: String(id), name: String(val) };
        });
    }

    function findItem(val, config) {
        const items = normalizeItems(config?.field_items);
        if (!items) return null;
        return items.find(item => String(item.id) === String(val)) ?? null;
    }

    function getLabel(val, config) {
        const item = findItem(val, config);
        if (item) return item.name;
        return DEFAULTS[String(val)] ?? String(val);
    }

    function getColor(val, config) {
        const item = findItem(val, config);
        if (item?.color) return { bg: item.color, text: '#fff' };
        // Support style: {backgroundColor, color} format (used in git/repo.php, tasks/task.php etc.)
        if (item?.style?.backgroundColor) {
            return { bg: item.style.backgroundColor, text: item.style.color ?? '#fff' };
        }
        const idx = parseInt(val, 10);
        if (isNaN(idx) || idx < 0) return PALETTE[0];
        return PALETTE[idx % PALETTE.length];
    }

    function badge(val, config) {
        if (val === null || val === undefined || val === '') return '—';
        // If API returned an enriched object {id, name, ...} instead of a primitive, extract id
        if (typeof val === 'object') val = val.id ?? val;
        if (val === null || val === undefined) return '—';
        const { bg, text } = getColor(val, config);
        const label = getLabel(val, config);
        const items = normalizeItems(config?.field_items);
        const item = items ? items.find(i => String(i.id) === String(val)) : null;
        console.log('[badge]', { val, valType: typeof val, isArrayFI: Array.isArray(config?.field_items), items, item, itemName: item?.name, itemNameType: typeof item?.name, label, labelType: typeof label, bg, text });
        return `<span style="display:inline-block;padding:2px 10px;border-radius:9999px;background:${bg};color:${text};font-size:11px;font-weight:600;white-space:nowrap">${label}</span>`;
    }

    // Exported helper: normalizes field_items to [[id, label], ...] for <select> rendering
    window.FieldComponents.statusSelectItems = function(field_items) {
        const items = normalizeItems(field_items);
        if (items) return items.map(i => [i.id, i.name]);
        return Object.entries(DEFAULTS);
    };

    window.FieldComponents.status = {
        index({ value, config }) { return badge(value, config); },
        show({ value, config })  { return badge(value, config); },
        render({ mode, ...props }) { return badge(props.value, props.config); },
    };
})();
