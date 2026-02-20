// public/assets/js/components/tabulatorFilterMenu.js
(function () {
    class DTFilterMenu {
        constructor(opts = {}) {
            this.opts = opts;

            this.el = null;
            this.isOpenFlag = false;
            this.docHandler = null;

            this.activeField = null;

            // flatpickr instances
            this.fp = [];

            // какой календарь сейчас открыт
            this._activeFp = null;

            // защита от закрытия меню при работе с flatpickr
            this._fpInteracting = false;
            this._fpCloseTimer = null;

            // one-time style injected?
            this._styleInjected = false;

            // bound handlers (so we can remove)
            this._panelMouseDownHandler = null;
            this._panelFocusInHandler = null;
        }

        isOpen() {
            return !!this.isOpenFlag;
        }

        ensure() {
            if (!this._styleInjected) {
                this._injectFlatpickrFixStyles();
                this._styleInjected = true;
            }

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

        _injectFlatpickrFixStyles() {
            const id = 'dt-flatpickr-fixes';
            if (document.getElementById(id)) return;

            const st = document.createElement('style');
            st.id = id;
            st.textContent = `
                /* ✅ flatpickr должен быть выше Tabulator/menus/modals */
                .flatpickr-calendar{
                    z-index: 2147483647 !important;
                }
                .flatpickr-calendar.open{
                    display: block;
                }
            `;
            document.head.appendChild(st);
        }

        close() {
            this.isOpenFlag = false;
            this.activeField = null;

            this._destroyPickers();
            this._detachPanelCloseCalendarHandlers();

            if (this.el) this.el.style.display = 'none';
            if (this.docHandler) {
                document.removeEventListener('mousedown', this.docHandler, true);
                this.docHandler = null;
            }
        }

        toggleNear(btnEl) {
            const field = btnEl.getAttribute('data-field');
            if (!field) return;

            if (this.isOpen() && this.activeField === field) {
                this.close();
                return;
            }
            this.openNear(btnEl);
        }

        openNear(btnEl) {
            this.ensure();

            const field = btnEl.getAttribute('data-field');
            if (!field) return;

            this.activeField = field;

            this.render(field);

            const r = btnEl.getBoundingClientRect();
            const w = 360;
            const pad = 8;

            let left = Math.round(r.right - w);
            left = Math.max(pad, Math.min(left, window.innerWidth - w - pad));

            let top = Math.round(r.bottom + 8);
            top = Math.max(pad, Math.min(top, window.innerHeight - 240 - pad));

            this.el.style.left = left + 'px';
            this.el.style.top = top + 'px';
            this.el.style.display = 'block';
            this.isOpenFlag = true;

            // ✅ закрытие календаря по клику/фокусу внутри панели (вне календаря)
            this._attachPanelCloseCalendarHandlers();

            // клик вне меню — закрыть меню
            if (this.docHandler) {
                document.removeEventListener('mousedown', this.docHandler, true);
            }

            setTimeout(() => {
                this.docHandler = (e) => {
                    if (!this.isOpen()) return;

                    // если в этот момент идёт взаимодействие с календарём — меню не трогаем
                    if (this._fpInteracting) return;

                    // игнорируем всё, что связано с flatpickr (календарь в body!)
                    const inFlatpickrCalendar = e.target.closest && e.target.closest('.flatpickr-calendar');
                    const inFlatpickrWrapper  = e.target.closest && e.target.closest('.flatpickr-wrapper');
                    const isFlatpickrInput    = e.target.classList && (
                        e.target.classList.contains('flatpickr-input') ||
                        e.target.classList.contains('flatpickr-alt-input')
                    );

                    if (inFlatpickrCalendar || inFlatpickrWrapper || isFlatpickrInput) return;

                    const inMenu = this.el && this.el.contains(e.target);
                    const onBtn = e.target.closest && e.target.closest('.dt-filter-btn');

                    if (!inMenu && !onBtn) this.close();
                };
                document.addEventListener('mousedown', this.docHandler, true);
            }, 0);
        }

        // ---------------- helpers ----------------
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

        _destroyPickers() {
            try {
                if (this._fpCloseTimer) {
                    clearTimeout(this._fpCloseTimer);
                    this._fpCloseTimer = null;
                }
                this._fpInteracting = false;
                this._activeFp = null;

                (this.fp || []).forEach(inst => {
                    try { inst && inst.destroy && inst.destroy(); } catch (e) {}
                });
            } finally {
                this.fp = [];
            }
        }

        _closeActiveCalendar() {
            if (!this._activeFp) return;
            try { this._activeFp.close(); } catch (e) {}
        }

        _isEventInsideFlatpickr(e) {
            const t = e?.target;
            if (!t) return false;

            const inCalendar = t.closest && t.closest('.flatpickr-calendar');
            const inWrapper = t.closest && t.closest('.flatpickr-wrapper');
            const isInput = t.classList && (
                t.classList.contains('flatpickr-input') ||
                t.classList.contains('flatpickr-alt-input')
            );
            return !!(inCalendar || inWrapper || isInput);
        }

        _attachPanelCloseCalendarHandlers() {
            if (!this.el) return;
            this._detachPanelCloseCalendarHandlers();

            // Закрывать календарь, если кликнули ВНУТРИ панели, но НЕ по календарю
            this._panelMouseDownHandler = (e) => {
                if (!this._activeFp) return;
                if (this._isEventInsideFlatpickr(e)) return;

                // клик внутри панели (она фиксирована), значит закрываем календарь
                this._closeActiveCalendar();
            };

            // Закрывать календарь, если фокус ушёл на другой инпут в панели
            this._panelFocusInHandler = (e) => {
                if (!this._activeFp) return;
                if (this._isEventInsideFlatpickr(e)) return;

                this._closeActiveCalendar();
            };

            // capture=true, чтобы отрабатывать раньше внутренних
            this.el.addEventListener('mousedown', this._panelMouseDownHandler, true);
            this.el.addEventListener('focusin', this._panelFocusInHandler, true);
        }

        _detachPanelCloseCalendarHandlers() {
            if (!this.el) return;

            if (this._panelMouseDownHandler) {
                this.el.removeEventListener('mousedown', this._panelMouseDownHandler, true);
                this._panelMouseDownHandler = null;
            }
            if (this._panelFocusInHandler) {
                this.el.removeEventListener('focusin', this._panelFocusInHandler, true);
                this._panelFocusInHandler = null;
            }
        }

        _initDateTimePickers() {
            if (!window.flatpickr) return;

            const ru = window.flatpickr?.l10ns?.ru || null;

            const optsBase = {
                enableTime: true,
                enableSeconds: true,
                time_24hr: true,

                // сохраняем значение в input в формате Y-m-d H:i:s
                dateFormat: "Y-m-d H:i:S",

                // отображаем пользователю dd.mm.yyyy HH:ii
                altInput: true,
                altFormat: "d.m.Y H:i",

                locale: ru ? Object.assign({}, ru, { firstDayOfWeek: 1 }) : undefined,
                allowInput: true,

                // ❌ НЕ закрываем на change
                closeOnSelect: false,

                onOpen: (_selectedDates, _dateStr, instance) => {
                    // отмечаем, что сейчас взаимодействуем с календарём
                    this._fpInteracting = true;
                    this._activeFp = instance;

                    if (this._fpCloseTimer) {
                        clearTimeout(this._fpCloseTimer);
                        this._fpCloseTimer = null;
                    }
                },

                onClose: () => {
                    // отпускаем флаг чуть позже, чтобы не поймать “последний” mousedown
                    if (this._fpCloseTimer) clearTimeout(this._fpCloseTimer);
                    this._fpCloseTimer = setTimeout(() => {
                        this._fpInteracting = false;
                        this._activeFp = null;
                        this._fpCloseTimer = null;
                    }, 180);
                },
            };

            const inputs = this.el.querySelectorAll('input[data-dtp="1"]');
            inputs.forEach((inp) => {
                try {
                    const inst = window.flatpickr(inp, optsBase);
                    this.fp.push(inst);
                } catch (e) {}
            });
        }

        // ---------------- render ----------------
        render(field) {
            if (!this.el) return;

            this._destroyPickers();

            const col = this._getCol(field);
            if (!col) return;

            const kind = this._inferKind(col);

            // init state per kind if not exists / mismatch
            let state = this._getState(field);
            if (!state || state.kind !== kind) {
                if (kind === 'text') state = { kind, op: 'icontain', value: '' };
                else if (kind === 'bool') state = { kind, value: '' }; // 1|0|null|''
                else if (kind === 'datetime') state = { kind, op: '', value: '', from: '', to: '' };
                else if (kind === 'int') state = { kind, op: '=', value: '', list: '' };
                else state = { kind: 'text', op: 'icontain', value: '' };

                this._setState(field, state);
            }

            const title = col.title ?? field;

            // header with close X
            let html = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px;">
                    <div class="dt-menu-title" style="margin:0;">Фильтр: ${this._escapeHtml(title)}</div>
                    <button type="button" data-action="close"
                            style="width:26px;height:26px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;border:0;cursor:pointer;background:rgba(0,0,0,0.04);">
                        <i class="uil uil-times"></i>
                    </button>
                </div>
                <div class="dt-menu-form">
            `;

            // ---------- TEXT ----------
            if (kind === 'text') {
                html += `
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

            // ---------- BOOL ----------
            if (kind === 'bool') {
                html += `
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

            // ---------- DATETIME ----------
            if (kind === 'datetime') {
                const hasFP = !!window.flatpickr;
                const typeFromTo = hasFP ? 'text' : 'datetime-local';
                const dtAttr = hasFP ? 'data-dtp="1"' : '';

                html += `
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
                            <div class="dt-menu-label">Дата/время от (>=)</div>
                            <input ${dtAttr} data-k="from" type="${typeFromTo}" value="${this._escapeAttr(state.from ?? '')}" placeholder="дд.мм.гггг чч:мм">
                        </div>
                        <div class="dt-menu-row">
                            <div class="dt-menu-label">Дата/время по (<=)</div>
                            <input ${dtAttr} data-k="to" type="${typeFromTo}" value="${this._escapeAttr(state.to ?? '')}" placeholder="дд.мм.гггг чч:мм">
                        </div>
                    </div>
                `;
            }

            // ---------- INT (без режима, IN/NOT IN в операциях) ----------
            if (kind === 'int') {
                const op = String(state.op || '=').toLowerCase();
                const isList = (op === 'in' || op === 'not in');

                html += `
                    <div class="dt-menu-row">
                        <div class="dt-menu-label">Операция</div>
                        <select data-k="op">
                            <option value="=" ${op==='='?'selected':''}>=</option>
                            <option value="!=" ${op==='!='?'selected':''}>!=</option>
                            <option value=">" ${op==='>'?'selected':''}>&gt;</option>
                            <option value=">=" ${op==='>='?'selected':''}>&gt;=</option>
                            <option value="<" ${op==='<'?'selected':''}>&lt;</option>
                            <option value="<=" ${op==='<='?'selected':''}>&lt;=</option>
                            <option value="in" ${op==='in'?'selected':''}>IN</option>
                            <option value="not in" ${op==='not in'?'selected':''}>NOT IN</option>
                        </select>
                    </div>

                    <div class="dt-menu-row" data-block="num" style="${isList ? 'display:none' : ''}">
                        <div class="dt-menu-label">Значение</div>
                        <input data-k="value" type="number" value="${this._escapeAttr(state.value ?? '')}">
                    </div>

                    <div class="dt-menu-row" data-block="list" style="${isList ? '' : 'display:none'}">
                        <div class="dt-menu-label">Список значений</div>
                        <textarea data-k="list" placeholder="1,2,3 или 1 2 3">${this._escapeHtml(state.list ?? '')}</textarea>
                    </div>
                `;
            }

            html += `
                    <div class="dt-menu-actions">
                        <button class="btnx" type="button" data-action="clear">Сбросить</button>
                        <button class="btnx primary" type="button" data-action="apply">Применить</button>
                    </div>
                </div>
            `;

            this.el.innerHTML = html;

            // close X
            this.el.querySelector('[data-action="close"]')?.addEventListener('click', () => {
                this.close();
            });

            // bind data-k changes
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

            // special: int op toggles list/num blocks
            const opSel = this.el.querySelector('select[data-k="op"]');
            if (kind === 'int' && opSel) {
                opSel.addEventListener('change', () => {
                    const v = String(opSel.value || '').toLowerCase();
                    const st = this._getState(field) || {};
                    st.op = opSel.value;
                    this._setState(field, st);

                    const num = this.el.querySelector('[data-block="num"]');
                    const list = this.el.querySelector('[data-block="list"]');

                    const isList = (v === 'in' || v === 'not in');
                    if (num) num.style.display = isList ? 'none' : '';
                    if (list) list.style.display = isList ? '' : 'none';
                });
            }

            // buttons
            const btnApply = this.el.querySelector('[data-action="apply"]');
            const btnClear = this.el.querySelector('[data-action="clear"]');

            if (btnApply) {
                btnApply.addEventListener('click', async () => {
                    this.close();
                    if (this.opts.onApply) await this.opts.onApply(field);
                });
            }

            if (btnClear) {
                btnClear.addEventListener('click', async () => {
                    this._deleteState(field);
                    this.close();
                    if (this.opts.onClearField) await this.opts.onClearField(field);
                });
            }

            // init datetime pickers (flatpickr)
            if (kind === 'datetime') {
                this._initDateTimePickers();
            }
        }
    }

    window.DTFilterMenu = DTFilterMenu;
})();
