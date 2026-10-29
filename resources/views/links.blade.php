<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الروابط - الكتابة الخاصة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f6f9;
            color: #333;
            min-height: 100vh;
        }

        /* ---- Top Bar ---- */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 2rem;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
        }

        .topbar-title .icon-wrap {
            background: #ebf4ff;
            color: #3182ce;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .btn-add {
            background: #3182ce;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.2s;
        }
        .btn-add:hover { background: #2b6cb0; }

        /* ---- Page Layout ---- */
        .page-wrap {
            max-width: 860px;
            margin: 30px auto;
            padding: 0 1.5rem;
        }

        /* ---- Breadcrumb ---- */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #718096;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .crumb {
            cursor: pointer;
            color: #3182ce;
            font-weight: 500;
        }
        .crumb:hover { text-decoration: underline; }
        .crumb.active { color: #2d3748; cursor: default; font-weight: 600; }
        .crumb.active:hover { text-decoration: none; }
        .crumb-sep { color: #cbd5e0; font-size: 0.75rem; }

        /* ---- Panel ---- */
        .panel {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .panel-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafbfc;
        }

        .panel-header-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ---- List ---- */
        .link-list { list-style: none; }

        .link-item {
            display: flex;
            align-items: center;
            padding: 13px 20px;
            border-bottom: 1px solid #f0f4f8;
            cursor: pointer;
            transition: background 0.15s;
            gap: 14px;
        }
        .link-item:last-child { border-bottom: none; }
        .link-item:hover { background: #f7faff; }

        .link-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .link-icon.folder { background: #fffbeb; color: #d69e2e; }
        .link-icon.link   { background: #ebf4ff; color: #3182ce; }
        .link-icon.pdf    { background: #fff5f5; color: #e53e3e; }
        .link-icon.word   { background: #ebf8ff; color: #2b6cb0; }
        .link-icon.excel  { background: #f0fff4; color: #276749; }

        .link-info { flex: 1; min-width: 0; }
        .link-name { font-weight: 600; font-size: 0.95rem; color: #2d3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .link-url  { font-size: 0.78rem; color: #a0aec0; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .link-actions {
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .link-item:hover .link-actions { opacity: 1; }

        .icon-btn {
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #718096;
            font-size: 0.8rem;
            transition: all 0.15s;
        }
        .icon-btn:hover.edit  { border-color: #3182ce; color: #3182ce; background: #ebf4ff; }
        .icon-btn:hover.trash { border-color: #e53e3e; color: #e53e3e; background: #fff5f5; }

        .type-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            flex-shrink: 0;
        }
        .type-badge.folder { background: #fffbeb; color: #b7791f; }
        .type-badge.link   { background: #ebf4ff; color: #2b6cb0; }

        /* ---- Empty ---- */
        .empty {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }
        .empty i { font-size: 3rem; margin-bottom: 12px; display: block; }
        .empty p { font-size: 0.95rem; }

        /* ---- Modal ---- */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background: #fff;
            border-radius: 12px;
            width: 440px;
            max-width: 94vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .modal-head {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f4f8;
            font-size: 1rem;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #a0aec0;
            font-size: 1.1rem;
            line-height: 1;
            transition: color 0.2s;
        }
        .close-btn:hover { color: #2d3748; }

        .modal-body { padding: 22px; }

        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 0.85rem; font-weight: 600; color: #4a5568; margin-bottom: 6px; }
        .field input, .field select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.92rem;
            color: #2d3748;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field input:focus, .field select:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49,130,206,0.15);
        }

        .modal-foot {
            padding: 14px 22px;
            border-top: 1px solid #f0f4f8;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn { padding: 8px 20px; border-radius: 7px; border: none; font-family: 'Cairo', sans-serif; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .btn-cancel { background: #edf2f7; color: #718096; }
        .btn-save   { background: #3182ce; color: #fff; }
        .btn:hover  { opacity: 0.88; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-title">
        <div class="icon-wrap"><i class="fas fa-link"></i></div>
        الروابط والمجلدات
    </div>
    <button class="btn-add" onclick="openModal()">
        <i class="fas fa-plus"></i> إضافة
    </button>
</div>

<div class="page-wrap">
    <nav class="breadcrumb" id="breadcrumb"></nav>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-header-title">المحتوى</span>
        </div>
        <ul class="link-list" id="list"></ul>
    </div>
</div>

<!-- Modal -->
<div class="overlay" id="overlay">
    <div class="modal">
        <div class="modal-head">
            <span id="modalTitle">إضافة جديد</span>
            <button class="close-btn" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form id="itemForm">
                <input type="hidden" id="itemId">

                <div class="field">
                    <label>الاسم</label>
                    <input type="text" id="itemName" required placeholder="مثلاً: بوابة المحاكم">
                </div>

                <div class="field">
                    <label>النوع</label>
                    <select id="itemType" onchange="toggleUrl()">
                        <option value="link">رابط</option>
                        <option value="folder">مجلد</option>
                    </select>
                </div>

                <div class="field" id="urlField">
                    <label>الرابط</label>
                    <input type="text" id="itemUrl" placeholder="https://...">
                </div>
            </form>
        </div>
        <div class="modal-foot">
            <button class="btn btn-cancel" onclick="closeModal()">إلغاء</button>
            <button class="btn btn-save" onclick="submitForm()">حفظ</button>
        </div>
    </div>
</div>

<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let currentParentId = null;
    let navStack = [{ id: null, name: 'الرئيسية' }];

    async function api(url, opts = {}) {
        const res = await fetch(url, {
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            ...opts,
        });
        if (!res.ok) throw new Error(await res.text());
        if (res.status === 204) return null;
        return res.json();
    }

    async function load() {
        const qs = currentParentId ? `?parent_id=${currentParentId}` : '';
        const items = await api(`/links/list${qs}`);
        render(items);
    }

    function iconClass(item) {
        if (item.type === 'folder') return { cls: 'folder', icon: 'fa-folder' };
        const n = item.name.toLowerCase();
        if (n.includes('pdf'))              return { cls: 'pdf',   icon: 'fa-file-pdf' };
        if (n.includes('word') || n.includes('doc')) return { cls: 'word', icon: 'fa-file-word' };
        if (n.includes('excel'))            return { cls: 'excel', icon: 'fa-file-excel' };
        return { cls: 'link', icon: 'fa-link' };
    }

    function render(items) {
        const list = document.getElementById('list');
        list.innerHTML = '';

        if (!items.length) {
            list.innerHTML = `<li class="empty"><i class="fas fa-inbox"></i><p>لا يوجد محتوى. اضغط على "إضافة" للبدء.</p></li>`;
            renderBreadcrumb();
            return;
        }

        items.forEach(item => {
            const { cls, icon } = iconClass(item);
            const li = document.createElement('li');
            li.className = 'link-item';
            li.innerHTML = `
                <div class="link-icon ${cls}"><i class="fas ${icon}"></i></div>
                <div class="link-info">
                    <div class="link-name">${item.name}</div>
                    ${item.url ? `<div class="link-url">${item.url}</div>` : ''}
                </div>
                <span class="type-badge ${item.type}">${item.type === 'folder' ? 'مجلد' : 'رابط'}</span>
                <div class="link-actions">
                    <button class="icon-btn edit" title="تعديل"><i class="fas fa-pen"></i></button>
                    <button class="icon-btn trash" title="حذف"><i class="fas fa-trash"></i></button>
                </div>
            `;

            li.querySelector('.edit').addEventListener('click', e => { e.stopPropagation(); startEdit(item); });
            li.querySelector('.trash').addEventListener('click', e => { e.stopPropagation(); remove(item.id); });
            li.addEventListener('click', () => {
                if (item.type === 'folder') enter(item.id, item.name);
                else window.open(item.url, '_blank');
            });

            list.appendChild(li);
        });

        renderBreadcrumb();
    }

    function renderBreadcrumb() {
        const nav = document.getElementById('breadcrumb');
        nav.innerHTML = '';
        navStack.forEach((crumb, i) => {
            const span = document.createElement('span');
            span.className = `crumb ${i === navStack.length - 1 ? 'active' : ''}`;
            span.innerHTML = `<i class="fas fa-${i === 0 ? 'home' : 'folder'}"></i> ${crumb.name}`;
            if (i < navStack.length - 1) span.addEventListener('click', () => goTo(i));
            nav.appendChild(span);
            if (i < navStack.length - 1) {
                const sep = document.createElement('span');
                sep.className = 'crumb-sep';
                sep.innerHTML = '<i class="fas fa-chevron-left"></i>';
                nav.appendChild(sep);
            }
        });
    }

    function enter(id, name) {
        currentParentId = id;
        navStack.push({ id, name });
        load();
    }

    function goTo(index) {
        navStack = navStack.slice(0, index + 1);
        currentParentId = navStack[index].id;
        load();
    }

    // --- Modal ---
    const overlay = document.getElementById('overlay');

    function openModal(edit = false) {
        document.getElementById('modalTitle').textContent = edit ? 'تعديل' : 'إضافة جديد';
        overlay.style.display = 'flex';
        if (!edit) {
            document.getElementById('itemForm').reset();
            document.getElementById('itemId').value = '';
            toggleUrl();
        }
    }

    function closeModal() { overlay.style.display = 'none'; }

    function toggleUrl() {
        const type = document.getElementById('itemType').value;
        const field = document.getElementById('urlField');
        field.style.display = type === 'folder' ? 'none' : 'block';
        document.getElementById('itemUrl').required = type !== 'folder';
    }

    function startEdit(item) {
        document.getElementById('itemId').value = item.id;
        document.getElementById('itemName').value = item.name;
        document.getElementById('itemType').value = item.type;
        document.getElementById('itemUrl').value = item.url || '';
        openModal(true);
        toggleUrl();
    }

    async function submitForm() {
        const id   = document.getElementById('itemId').value;
        const body = JSON.stringify({
            name:      document.getElementById('itemName').value,
            type:      document.getElementById('itemType').value,
            url:       document.getElementById('itemUrl').value || null,
            parent_id: currentParentId,
        });
        if (id) await api(`/links/${id}`, { method: 'PUT', body });
        else     await api('/links',       { method: 'POST', body });
        closeModal();
        load();
    }

    async function remove(id) {
        if (!confirm('هل أنت متأكد من الحذف؟')) return;
        await api(`/links/${id}`, { method: 'DELETE' });
        load();
    }

    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });

    load();
</script>
</body>
</html>
