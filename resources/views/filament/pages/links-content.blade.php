<div id="lk-root" style="font-family: 'Cairo', sans-serif; direction: rtl;">
    <style>
        .lk-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .lk-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #6b7280;
            flex-wrap: wrap;
        }
        .lk-crumb { cursor: pointer; color: #0ea5e9; font-weight: 500; }
        .lk-crumb:hover { text-decoration: underline; }
        .lk-crumb.active { color: #1f2937; cursor: default; font-weight: 600; }
        .lk-crumb.active:hover { text-decoration: none; }
        .lk-crumb-sep { color: #d1d5db; font-size: 0.75rem; }

        .lk-add-btn {
            background: #0ea5e9; color: #fff; border: none;
            padding: 8px 18px; border-radius: 6px;
            font-size: 0.88rem; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 7px;
            transition: background 0.2s;
        }
        .lk-add-btn:hover { background: #0284c7; }

        .lk-panel { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; }
        .lk-panel-head {
            padding: 12px 18px; border-bottom: 1px solid #f3f4f6; background: #f9fafb;
            font-size: 0.78rem; font-weight: 600; color: #9ca3af;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .lk-list { list-style: none; margin: 0; padding: 0; }
        .lk-item {
            display: flex; align-items: center;
            padding: 12px 18px; border-bottom: 1px solid #f3f4f6;
            cursor: pointer; transition: background 0.15s; gap: 12px;
        }
        .lk-item:last-child { border-bottom: none; }
        .lk-item:hover { background: #f0f9ff; }

        .lk-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
        .lk-icon.folder { background: #fef9c3; color: #ca8a04; }
        .lk-icon.link   { background: #e0f2fe; color: #0284c7; }
        .lk-icon.pdf    { background: #fee2e2; color: #dc2626; }
        .lk-icon.word   { background: #dbeafe; color: #1d4ed8; }
        .lk-icon.excel  { background: #dcfce7; color: #15803d; }

        .lk-info { flex: 1; min-width: 0; }
        .lk-name { font-weight: 600; font-size: 0.92rem; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lk-url  { font-size: 0.76rem; color: #9ca3af; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .lk-badge { font-size: 0.68rem; font-weight: 600; padding: 2px 8px; border-radius: 10px; flex-shrink: 0; }
        .lk-badge.folder { background: #fef9c3; color: #a16207; }
        .lk-badge.link   { background: #e0f2fe; color: #0369a1; }

        .lk-actions { display: flex; gap: 5px; opacity: 0; transition: opacity 0.2s; }
        .lk-item:hover .lk-actions { opacity: 1; }

        .lk-icon-btn {
            background: none; border: 1px solid #e5e7eb; border-radius: 6px;
            width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #6b7280; font-size: 0.75rem; transition: all 0.15s;
        }
        .lk-icon-btn.edit:hover  { border-color: #0ea5e9; color: #0ea5e9; background: #e0f2fe; }
        .lk-icon-btn.trash:hover { border-color: #ef4444; color: #ef4444; background: #fee2e2; }

        .lk-empty { text-align: center; padding: 50px 20px; color: #9ca3af; }
        .lk-empty i { font-size: 2.5rem; margin-bottom: 10px; display: block; }
        .lk-empty p { font-size: 0.9rem; }

        .lk-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .lk-modal { background: #fff; border-radius: 12px; width: 420px; max-width: 94vw; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .lk-modal-head { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 0.98rem; font-weight: 700; color: #1f2937; display: flex; justify-content: space-between; align-items: center; }
        .lk-close { background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 1rem; }
        .lk-close:hover { color: #1f2937; }
        .lk-modal-body { padding: 20px; }
        .lk-field { margin-bottom: 14px; }
        .lk-field label { display: block; font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .lk-field input, .lk-field select {
            width: 100%; padding: 8px 11px; border: 1px solid #d1d5db; border-radius: 7px;
            font-size: 0.9rem; color: #1f2937; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box;
        }
        .lk-field input:focus, .lk-field select:focus { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,0.15); }
        .lk-modal-foot { padding: 12px 20px; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 8px; }
        .lk-btn { padding: 8px 18px; border-radius: 7px; border: none; font-size: 0.88rem; font-weight: 600; cursor: pointer; }
        .lk-btn-cancel { background: #f3f4f6; color: #6b7280; }
        .lk-btn-save   { background: #0ea5e9; color: #fff; }
        .lk-btn:hover  { opacity: 0.88; }
    </style>

    <div class="lk-toolbar">
        <nav class="lk-breadcrumb" id="lkBreadcrumb"></nav>
        <button class="lk-add-btn" onclick="lkOpenModal()">
            <i class="fas fa-plus"></i> إضافة
        </button>
    </div>

    <div class="lk-panel">
        <div class="lk-panel-head">المحتوى</div>
        <ul class="lk-list" id="lkList"></ul>
    </div>

    <div class="lk-overlay" id="lkOverlay">
        <div class="lk-modal">
            <div class="lk-modal-head">
                <span id="lkModalTitle">إضافة جديد</span>
                <button class="lk-close" onclick="lkCloseModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="lk-modal-body">
                <input type="hidden" id="lkId">
                <div class="lk-field">
                    <label>الاسم</label>
                    <input type="text" id="lkName" required placeholder="مثلاً: بوابة المحاكم">
                </div>
                <div class="lk-field">
                    <label>النوع</label>
                    <select id="lkType" onchange="lkToggleUrl()">
                        <option value="link">رابط</option>
                        <option value="folder">مجلد</option>
                    </select>
                </div>
                <div class="lk-field" id="lkUrlField">
                    <label>الرابط</label>
                    <input type="text" id="lkUrl" placeholder="https://...">
                </div>
            </div>
            <div class="lk-modal-foot">
                <button class="lk-btn lk-btn-cancel" onclick="lkCloseModal()">إلغاء</button>
                <button class="lk-btn lk-btn-save" onclick="lkSubmit()">حفظ</button>
            </div>
        </div>
    </div>

    <script>
        const lkCsrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        let lkParentId = null;
        let lkNavStack = [{ id: null, name: 'الرئيسية' }];

        async function lkApi(url, opts = {}) {
            const res = await fetch(url, {
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': lkCsrf },
                ...opts,
            });
            if (!res.ok) throw new Error(await res.text());
            if (res.status === 204) return null;
            return res.json();
        }

        async function lkLoad() {
            const qs = lkParentId ? `?parent_id=${lkParentId}` : '';
            const items = await lkApi(`/links/list${qs}`);
            lkRender(items);
        }

        function lkIconInfo(item) {
            if (item.type === 'folder') return { cls: 'folder', icon: 'fa-folder' };
            const n = item.name.toLowerCase();
            if (n.includes('pdf'))  return { cls: 'pdf',   icon: 'fa-file-pdf' };
            if (n.includes('word') || n.includes('doc')) return { cls: 'word', icon: 'fa-file-word' };
            if (n.includes('excel')) return { cls: 'excel', icon: 'fa-file-excel' };
            return { cls: 'link', icon: 'fa-link' };
        }

        function lkRender(items) {
            const list = document.getElementById('lkList');
            list.innerHTML = '';
            if (!items.length) {
                list.innerHTML = '<li class="lk-empty"><i class="fas fa-inbox"></i><p>لا يوجد محتوى. اضغط على "إضافة" للبدء.</p></li>';
                lkRenderBreadcrumb();
                return;
            }
            items.forEach(item => {
                const { cls, icon } = lkIconInfo(item);
                const li = document.createElement('li');
                li.className = 'lk-item';
                li.innerHTML =
                    '<div class="lk-icon ' + cls + '"><i class="fas ' + icon + '"></i></div>' +
                    '<div class="lk-info">' +
                        '<div class="lk-name">' + item.name + '</div>' +
                        (item.url ? '<div class="lk-url">' + item.url + '</div>' : '') +
                    '</div>' +
                    '<span class="lk-badge ' + item.type + '">' + (item.type === 'folder' ? 'مجلد' : 'رابط') + '</span>' +
                    '<div class="lk-actions">' +
                        '<button class="lk-icon-btn edit" title="تعديل"><i class="fas fa-pen"></i></button>' +
                        '<button class="lk-icon-btn trash" title="حذف"><i class="fas fa-trash"></i></button>' +
                    '</div>';
                li.querySelector('.edit').addEventListener('click', e => { e.stopPropagation(); lkStartEdit(item); });
                li.querySelector('.trash').addEventListener('click', e => { e.stopPropagation(); lkRemove(item.id); });
                li.addEventListener('click', () => {
                    if (item.type === 'folder') lkEnter(item.id, item.name);
                    else window.open(item.url, '_blank');
                });
                list.appendChild(li);
            });
            lkRenderBreadcrumb();
        }

        function lkRenderBreadcrumb() {
            const nav = document.getElementById('lkBreadcrumb');
            nav.innerHTML = '';
            lkNavStack.forEach((crumb, i) => {
                const span = document.createElement('span');
                span.className = 'lk-crumb' + (i === lkNavStack.length - 1 ? ' active' : '');
                span.innerHTML = '<i class="fas fa-' + (i === 0 ? 'home' : 'folder') + '"></i> ' + crumb.name;
                if (i < lkNavStack.length - 1) span.addEventListener('click', () => lkGoTo(i));
                nav.appendChild(span);
                if (i < lkNavStack.length - 1) {
                    const sep = document.createElement('span');
                    sep.className = 'lk-crumb-sep';
                    sep.innerHTML = '<i class="fas fa-chevron-left"></i>';
                    nav.appendChild(sep);
                }
            });
        }

        function lkEnter(id, name) { lkParentId = id; lkNavStack.push({ id, name }); lkLoad(); }
        function lkGoTo(i) { lkNavStack = lkNavStack.slice(0, i + 1); lkParentId = lkNavStack[i].id; lkLoad(); }

        const lkOverlay = document.getElementById('lkOverlay');

        function lkOpenModal(edit = false) {
            document.getElementById('lkModalTitle').textContent = edit ? 'تعديل' : 'إضافة جديد';
            lkOverlay.style.display = 'flex';
            if (!edit) {
                document.getElementById('lkId').value = '';
                document.getElementById('lkName').value = '';
                document.getElementById('lkType').value = 'link';
                document.getElementById('lkUrl').value = '';
                lkToggleUrl();
            }
        }

        function lkCloseModal() { lkOverlay.style.display = 'none'; }

        function lkToggleUrl() {
            const type = document.getElementById('lkType').value;
            document.getElementById('lkUrlField').style.display = type === 'folder' ? 'none' : 'block';
        }

        function lkStartEdit(item) {
            document.getElementById('lkId').value = item.id;
            document.getElementById('lkName').value = item.name;
            document.getElementById('lkType').value = item.type;
            document.getElementById('lkUrl').value = item.url || '';
            lkOpenModal(true);
            lkToggleUrl();
        }

        async function lkSubmit() {
            const id   = document.getElementById('lkId').value;
            const body = JSON.stringify({
                name:      document.getElementById('lkName').value,
                type:      document.getElementById('lkType').value,
                url:       document.getElementById('lkUrl').value || null,
                parent_id: lkParentId,
            });
            if (id) await lkApi('/links/' + id, { method: 'PUT', body });
            else     await lkApi('/links',       { method: 'POST', body });
            lkCloseModal();
            lkLoad();
        }

        async function lkRemove(id) {
            if (!confirm('هل أنت متأكد من الحذف؟')) return;
            await lkApi('/links/' + id, { method: 'DELETE' });
            lkLoad();
        }

        lkOverlay.addEventListener('click', e => { if (e.target === lkOverlay) lkCloseModal(); });

        lkLoad();
    </script>
</div>
