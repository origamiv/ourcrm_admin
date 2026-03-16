// public/assets/js/components/deleteModal.js
(function () {
    class DeleteModal {
        constructor(opts = {}) {
            this.opts = opts;

            this.modalEl = null;
            this.isOpenFlag = false;
            this.currentId = null;

            this._onKeyDown = this._onKeyDown.bind(this);
        }

        ensure() {
            if (this.modalEl) return;

            const el = document.createElement('div');
            el.style.display = 'none';
            el.className = 'dt-delmodal fixed inset-0 z-[99999] flex items-center justify-center bg-black/50';

            el.innerHTML = `
                <div class="dt-delmodal-card bg-white dark:bg-[#0e1726] rounded-xl shadow-xl max-w-md p-6" role="dialog" aria-modal="true">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="uil uil-exclamation-triangle text-danger text-2xl"></i>
                        <div class="text-lg font-semibold">Подтверждение удаления</div>
                    </div>

                    <div class="text-white-dark mb-6">
                        Вы действительно хотите удалить эту запись?
                    </div>

                    <div class="flex justify-end gap-3">
                        <button class="btn btn-outline-secondary" type="button" data-action="cancel">Нет</button>
                        <button class="btn btn-danger" type="button" data-action="ok">Да</button>
                    </div>
                </div>
            `;

            // клик по фону — закрыть
            el.addEventListener('mousedown', (e) => {
                const card = el.querySelector('.dt-delmodal-card');
                if (!card) return;
                if (e.target === el) this.close();
            });

            // кнопки
            el.querySelector('[data-action="cancel"]')?.addEventListener('click', () => this.close());
            el.querySelector('[data-action="ok"]')?.addEventListener('click', async () => {
                if (!this.currentId) return;
                if (this.opts.onConfirm) await this.opts.onConfirm(this.currentId);
            });

            document.body.appendChild(el);
            this.modalEl = el;
        }

        open(id) {
            this.ensure();
            this.currentId = id;
            this.isOpenFlag = true;
            this.modalEl.style.display = 'flex';
            document.addEventListener('keydown', this._onKeyDown);
        }

        close() {
            this.currentId = null;
            this.isOpenFlag = false;
            if (this.modalEl) this.modalEl.style.display = 'none';
            document.removeEventListener('keydown', this._onKeyDown);
        }

        _onKeyDown(e) {
            if (e.key === 'Escape') this.close();
        }

        isOpen() {
            return !!this.isOpenFlag;
        }
    }

    window.DeleteModal = DeleteModal;
})();
