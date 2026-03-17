(function () {
    window.FieldComponents = window.FieldComponents || {};

    // Color palette: index = status value
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

    function getColor(val) {
        const idx = parseInt(val, 10);
        if (isNaN(idx) || idx < 0) return PALETTE[0];
        return PALETTE[idx % PALETTE.length];
    }

    function getLabel(val, config) {
        if (config?.field_items) {
            const label = config.field_items[String(val)];
            if (label !== undefined) return label;
        }
        const defaults = { '0': 'Неактивен', '1': 'Активен' };
        return defaults[String(val)] ?? String(val);
    }

    function badge(val, config) {
        if (val === null || val === undefined || val === '') return '—';
        const { bg, text } = getColor(val);
        const label = getLabel(val, config);
        return `<span style="display:inline-block;padding:2px 10px;border-radius:9999px;background:${bg};color:${text};font-size:11px;font-weight:600;white-space:nowrap">${label}</span>`;
    }

    window.FieldComponents.status = {
        index({ value, config }) { return badge(value, config); },
        show({ value, config })  { return badge(value, config); },
        render({ mode, ...props }) { return badge(props.value, props.config); },
    };
})();
