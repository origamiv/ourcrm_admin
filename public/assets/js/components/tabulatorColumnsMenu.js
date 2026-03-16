// public/assets/js/components/tabulatorColumnsMenu.js
(function () {
    class DTColumnsMenu {
        constructor(opts = {}) {
            this.opts = opts;

            this.el = null;
            this.isOpenFlag = false;
            this.docHandler = null;
        }

        isOpen() {
            return !!this.isOpenFlag;
        }

        ensure() {
            if (this.el) return;

            const el = document.createElement('div');
            el.className = 'dt-menu';
            el.style.display = 'none';

            // клики внутри меню не должны закрывать меню
            el.addEventListener('mousedown', (e) => e.stopPropagation());
            el.addEventListener('click', (e) => e.stopPropagation());

            document.body.appendChild(el);
            this.el = el;
        }

        close() {
            this.isOpenFlag = false;
            if (this.el) this.el.style.display = 'none';

            if (this.docHandler) {
                document.removeEventListener('mousedown', this.docHandler, true);
                this.docHandler = null;
            }
        }

        toggleNear(btnEl) {
            if (this.isOpen()) {
                this.close();
                return;
            }
            this.openNear(btnEl);
        }

        openNear(btnEl) {
            this.ensure();
            this.render();

            const r = btnEl.getBoundingClientRect();
            const w = 320;
            const pad = 8;

            let left = Math.round(r.right - w);
            left = Math.max(pad, Math.min(left, window.innerWidth - w - pad));

            let top = Math.round(r.bottom + 8);
            top = Math.max(pad, Math.min(top, window.innerHeight - 200 - pad));

            this.el.style.left = left + 'px';
            this.el.style.top = top + 'px';
            this.el.style.display = 'block';
            this.isOpenFlag = true;

            // клик вне меню — закрыть
            if (this.docHandler) {
                document.removeEventListener('mousedown', this.docHandler, true);
            }

            setTimeout(() => {
                this.docHandler = (e) => {
                    if (!this.isOpen()) return;

                    const inMenu = this.el && this.el.contains(e.target);
                    const onBtn = e.target.closest && e.target.closest('.dt-colmenu-btn');

                    if (!inMenu && !onBtn) this.close();
                };
                document.addEventListener('mousedown', this.docHandler, true);
            }, 0);
        }

        // helpers
        _escapeHtml(str) {
            return this.opts.escapeHtml ? this.opts.escapeHtml(str) : String(str ?? '');
        }
        _escapeAttr(str) {
            return this.opts.escapeAttr ? this.opts.escapeAttr(str) : this._escapeHtml(str);
        }

        render() {
            if (!this.el) return;

            const tabulator = this.opts.getTabulator ? this.opts.getTabulator() : null;
            if (!tabulator) return;

            const cols = tabulator.getColumns()
                .filter(c => (c.getField && c.getField()) && c.getField() !== '__actions');

            let html = `<div class="dt-menu-title">Колонки</div>`;

            cols.forEach((c) => {
                const def = c.getDefinition();
                const field = c.getField();
                const title = def.title ?? field;
                const visible = c.isVisible();

                html += `
                    <div class="dt-menu-item" data-field="${this._escapeAttr(field)}">
                        <span class="dt-menu-mark">${visible ? '✓' : ''}</span>
                        <div class="text-sm">${this._escapeHtml(title)}</div>
                    </div>
                `;
            });

            this.el.innerHTML = html;

            this.el.querySelectorAll('.dt-menu-item').forEach((rowEl) => {
                rowEl.addEventListener('click', () => {
                    const field = rowEl.getAttribute('data-field');
                    if (!field) return;

                    const col = tabulator.getColumn(field);
                    if (!col) return;

                    if (col.isVisible()) col.hide();
                    else col.show();

                    // меню не закрываем — обновляем галочки
                    this.render();
                });
            });
        }
    }

    window.DTColumnsMenu = DTColumnsMenu;
})();
