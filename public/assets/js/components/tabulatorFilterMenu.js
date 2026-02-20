// public/assets/js/components/tabulatorFilterMenu.js
(function () {
    class DTFilterMenu {
        constructor(opts = {}) {
            this.opts = opts;

            this.el = null;
            this.isOpenFlag = false;
            this.docHandler = null;

            this.activeField = null;
        }

        isOpen() {
            return !!this.isOpenFlag;
        }

        getActiveField() {
            return this.activeField;
        }

        ensure() {
            if (this.el) return;

            const el = document.createElement('div');
            el.className = 'dt-menu';
            el.style.display = 'none';

            // не даем кликам уходить наружу (чтобы меню не закрывалось при клике внутри)
            el.addEventListener('mousedown', (e) => e.stopPropagation());
            el.addEventListener('click', (e) => e.stopPropagation());

            document.body.appendChild(el);
            this.el = el;
        }

        close() {
            this.isOpenFlag = false;
            this.activeField = null;

            if (this.el) this.el.style.display = 'none';
            if (this.docHandler) {
                document.removeEventListener('mousedown', this.docHandler, true);
                this.docHandler = null;
            }
        }

        toggleNear(btnEl) {
            const field = btnEl?.getAttribute?.('data-field');
            if (!field) return;

            if (this.isOpen() && this.getActiveField() === field) {
                this.close();
                return;
            }
            this.openNear(btnEl);
        }

        openNear(btnEl) {
            this.ensure();

            const field = btnEl.getAttribute('data-field');
            this.activeField = field;

            this.render(field);

            const r = btnEl.getBoundingClientRect();
            const w = 320;
            const pad = 8;

            let left = Math.round(r.right - w);
            left = Math.max(pad, Math.min(left, window.innerWidth - w - pad));

            let top = Math.round(r.bottom + 8);
            top = Math.max(pad, Math.min(top, window.innerHeight - 220 - pad));

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
                    const onBtn = e.target.closest && e.target.closest('.dt-filter-btn');

                    if (!inMenu && !onBtn) this.close();
                };
                document.addEventListener('mousedown', this.docHandler, true);
            }, 0);
        }

        // ---------- helpers from host ----------
        _escapeHtml(str) {
            return this.opts.escapeHtml ? this.opts.escapeHtml(str) : String(str ?? '');
        }
        _escapeAttr(str) {
            return this.opts.escapeAttr ? this.opts.escapeAttr(str) : this._escapeHtml(str);
        }

        _getCol(field) {
            return this.opts.getCol ? this.opts.getCol(field) : null;
        }
        _inferKind(col) {
            return this.opts.inferKind ? this.opts.inferKind(col) : 'text';
        }

        _getState(field) {
            return this.opts.getState ? this.opts.getState(field) : null;
        }
        _setState(field, state) {
            if (this.opts.setState) this.opts.setState(field, state);
        }
        _deleteState(field) {
            if (this.opts.deleteState) this.opts.deleteState(field);
        }

        async _apply() {
            if (this.opts.onApply) await this.opts.onApply();
        }
        async _clearField(field) {
            if (this.opts.onClearField) await this.opts.onClearField(field);
        }

        // ---------- render ----------
        render(field) {
            if (!this.el) return;

            const col = this._getCol(field);
            if (!col) return;

            const kind = this._inferKind(col);

            // init state если нет
            let state = this._getState(field);
            if (!state || state.kind !== kind) {
                if (kind === 'text') state = { kind, op: 'icontain', value: '' };
                else if (kind === 'bool') state = { kind, value: '' };
                else if (kind === 'datetime') state = { kind, op: '', value: '', from: '', to: '' };
                else if (kind === 'int') state = { kind, mode: 'cmp', op: '=', value: '', list: '' };
                else state = { kind: 'text', op: 'icontain', value: '' };

                this._setState(field, state);
            }

            const title = col.title ?? field;

            let body = `<div class="dt-menu-title">Фильтр: ${this._escapeHtml(title)}</div>`;
            body += `<div class="dt-menu-form">`;

            if (kind === 'text') {
                body += `
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Операция</div>
                        <select data-k="op">
                            <option value="like" ${state.op==='like'?'selected':''}>Начинается (с учётом регистра)</option>
                            <option value="ilike" ${state.op==='ilike'?'selected':''}>Начинается (без учёта регистра)</option>
                            <option value="contain" ${state.op==='contain'?'selected':''}>Содержит (с учётом регистра)</option>
                            <option value="icontain" ${state.op==='icontain'?'selected':''}>Содержит (без учёта регистра)</option>
                            <option value="=" ${state.op==='='?'selected':''}>=</option>
                            <option value="!=" ${state.op==='!='?'selected':''}>!=</option>
                        </select>
                    </div>
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Значение</div>
                        <input data-k="value" type="text" value="${this._escapeAttr(state.value ?? '')}">
                    </div>
                `;
            }

            if (kind === 'bool') {
                body += `
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Значение</div>
                        <select data-k="value">
                            <option value="" ${String(state.value)===''?'selected':''}>—</option>
                            <option value="1" ${String(state.value)==='1'?'selected':''}>Да</option>
                            <option value="0" ${String(state.value)==='0'?'selected':''}>Нет</option>
                            <option value="null" ${String(state.value)==='null'?'selected':''}>Не определено</option>
                        </select>
                    </div>
                `;
            }

            if (kind === 'datetime') {
                body += `
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Текстовый фильтр (опционально)</div>
                        <select data-k="op">
                            <option value="" ${(state.op||'')===''?'selected':''}>—</option>
                            <option value="like" ${state.op==='like'?'selected':''}>Начинается (с учётом регистра)</option>
                            <option value="ilike" ${state.op==='ilike'?'selected':''}>Начинается (без учёта регистра)</option>
                            <option value="contain" ${state.op==='contain'?'selected':''}>Содержит (с учётом регистра)</option>
                            <option value="icontain" ${state.op==='icontain'?'selected':''}>Содержит (без учёта регистра)</option>
                            <option value="=" ${state.op==='='?'selected':''}>=</option>
                            <option value="!=" ${state.op==='!='?'selected':''}>!=</option>
                        </select>
                        <input data-k="value" type="text" value="${this._escapeAttr(state.value ?? '')}" placeholder="например 2026-02">
                    </div>

                    <div class="dt-menu-row-2">
                        <div class="dt-menu-row">
                            <div class="dt-menu-label">Дата от (>=)</div>
                            <input data-k="from" type="date" value="${this._escapeAttr(state.from ?? '')}">
                        </div>
                        <div class="dt-menu-row">
                            <div class="dt-menu-label">Дата по (<=)</div>
                            <input data-k="to" type="date" value="${this._escapeAttr(state.to ?? '')}">
                        </div>
                    </div>
                `;
            }

            if (kind === 'int') {
                const mode = String(state.mode || 'cmp');
                body += `
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Режим</div>
                        <select data-k="mode">
                            <option value="cmp" ${mode==='cmp'?'selected':''}>Сравнение</option>
                            <option value="in" ${mode==='in'?'selected':''}>IN</option>
                            <option value="not_in" ${mode==='not_in'?'selected':''}>NOT IN</option>
                        </select>
                    </div>

                    <div class="dt-menu-row" data-block="cmp" style="${mode==='cmp'?'':'display:none'}">
                        <div class="dt-menu-label">Операция</div>
                        <select data-k="op">
                            <option value="=" ${state.op==='='?'selected':''}>=</option>
                            <option value="!=" ${state.op==='!='?'selected':''}>!=</option>
                            <option value=">" ${state.op==='>'?'selected':''}>&gt;</option>
                            <option value=">=" ${state.op==='>='?'selected':''}>&gt;=</option>
                            <option value="<" ${state.op==='<'?'selected':''}>&lt;</option>
                            <option value="<=" ${state.op==='<='?'selected':''}>&lt;=</option>
                        </select>
                        <div class="dt-menu-label">Значение</div>
                        <input data-k="value" type="number" value="${this._escapeAttr(state.value ?? '')}">
                    </div>

                    <div class="dt-menu-row" data-block="list" style="${(mode==='in'||mode==='not_in')?'':'display:none'}">
                        <div class="dt-menu-label">Список значений</div>
                        <textarea data-k="list" placeholder="1,2,3 или 1 2 3">${this._escapeHtml(state.list ?? '')}</textarea>
                    </div>
                `;
            }

            body += `
                <div class="dt-menu-actions">
                    <button class="btnx" type="button" data-action="clear">Сбросить</button>
                    <button class="btnx primary" type="button" data-action="apply">Применить</button>
                </div>
            `;
            body += `</div>`;

            this.el.innerHTML = body;

            // mode switch
            const modeSel = this.el.querySelector('select[data-k="mode"]');
            if (modeSel) {
                modeSel.addEventListener('change', () => {
                    const st = this._getState(field) || {};
                    st.mode = modeSel.value;
                    this._setState(field, st);

                    const cmp = this.el.querySelector('[data-block="cmp"]');
                    const list = this.el.querySelector('[data-block="list"]');
                    if (cmp)  cmp.style.display = (st.mode === 'cmp') ? '' : 'none';
                    if (list) list.style.display = (st.mode === 'in' || st.mode === 'not_in') ? '' : 'none';
                });
            }

            // inputs
            this.el.querySelectorAll('[data-k]').forEach(inp => {
                const k = inp.getAttribute('data-k');
                if (!k) return;

                const handler = () => {
                    const st = this._getState(field) || {};
                    st[k] = inp.value;
                    this._setState(field, st);
                };
                inp.addEventListener('input', handler);
                inp.addEventListener('change', handler);
            });

            const btnApply = this.el.querySelector('[data-action="apply"]');
            const btnClear = this.el.querySelector('[data-action="clear"]');

            if (btnApply) {
                btnApply.addEventListener('click', async () => {
                    this.close();
                    await this._apply();
                });
            }

            if (btnClear) {
                btnClear.addEventListener('click', async () => {
                    this._deleteState(field);
                    this.close();
                    await this._clearField(field);
                });
            }
        }
    }

    window.DTFilterMenu = DTFilterMenu;
})();
