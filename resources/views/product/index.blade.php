@extends('layouts.header')

@section('title', 'إدارة المنتجات')

@section('content')
@vite('resources/css/product-index.css')
   {{-- ✅ شريط الفلترة المتقدم --}}

    {{-- ✅ Header --}}
    <div class="page-header">
        <div>
            <h1>
                <span class="header-emoji">📦</span> إدارة المنتجات
            </h1>
            <div class="breadcrumb">
                <a href="{{ url('/') }}">🏠 الرئيسية</a>
                <span class="separator">›</span>
                <span class="current">المنتجات</span>
            </div>
        </div>
        <a href="{{ route('product.create') }}" class="btn btn-primary btn-lg">
            <span>➕</span> إضافة منتج جديد
        </a>
    </div>

    {{-- ✅ إحصائيات --}}
    <div class="stats-grid">
        <div class="stat-card bg-blue">
            <div class="stat-icon-box">
                <span class="stat-emoji">📦</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['total'] }}</span>
                <span class="stat-label">إجمالي المنتجات</span>
            </div>
        </div>
        <div class="stat-card bg-green">
            <div class="stat-icon-box">
                <span class="stat-emoji">✅</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['active'] }}</span>
                <span class="stat-label">منتجات نشطة</span>
            </div>
        </div>
        <div class="stat-card bg-orange">
            <div class="stat-icon-box">
                <span class="stat-emoji">📊</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['stock'] }}</span>
                <span class="stat-label">إجمالي المخزون</span>
            </div>
        </div>
        <div class="stat-card bg-purple">
            <div class="stat-icon-box">
                <span class="stat-emoji">🏷️</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['variants'] }}</span>
                <span class="stat-label">إجمالي المتغيرات</span>
            </div>
        </div>
    </div>

    {{-- ✅ شريط البحث والفلترة --}}
   <div class="filter-section">
    {{-- السطر الأول: البحث والترتيب --}}
    <div class="filter-row">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" name="search" 
                   value="{{ request('search') }}" 
                   placeholder="ابحث عن منتج، SKU، وصف..." 
                   class="search-input">
        </div>
        
        <select id="sortBy" name="sort_by" class="filter-select" onchange="applyFilters()">
            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>🆕 الأحدث</option>
            <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>📅 الأقدم</option>
            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>💰 السعر: من الأقل</option>
            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>💎 السعر: من الأعلى</option>
            <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>🔤 الاسم: أ-ي</option>
            <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>🔤 الاسم: ي-أ</option>
            <option value="most_orders" {{ request('sort_by') == 'most_orders' ? 'selected' : '' }}>🔥 الأكثر طلباً</option>
        </select>
    </div>

    {{-- السطر الثاني: الفلاتر المتقدمة --}}
    <div class="filter-row">
        {{-- فلتر القسم --}}
        <select id="categoryFilter" name="category_id" class="filter-select" onchange="applyFilters()">
            <option value="">📂 كل الأقسام</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        {{-- فلتر العلامة التجارية --}}
        <select id="brandFilter" name="brand_id" class="filter-select" onchange="applyFilters()">
            <option value="">🏷️ كل العلامات التجارية</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->brand_id }}" {{ request('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        {{-- فلتر الحالة --}}
        <select id="activeFilter" name="is_active" class="filter-select" onchange="applyFilters()">
            <option value="">📊 كل الحالات</option>
            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>✅ نشط</option>
            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>❌ غير نشط</option>
        </select>

        {{-- فلتر المخزون --}}
        <select id="stockFilter" name="stock_status" class="filter-select" onchange="applyFilters()">
            <option value="">📦 كل المخزون</option>
            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>🟢 متوفر</option>
            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>🔴 غير متوفر</option>
        </select>
    </div>

    {{-- السطر الثالث: فلتر السعر --}}
    <div class="filter-row price-filter-row">
        <label style="font-size:13px;color:#64748b;">💰 فلتر السعر:</label>
        <input type="number" id="priceFrom" name="price_from" 
               value="{{ request('price_from') }}" 
               placeholder="من" 
               class="price-input" 
               onchange="applyFilters()">
        <span style="color:#94a3b8;">-</span>
        <input type="number" id="priceTo" name="price_to" 
               value="{{ request('price_to') }}" 
               placeholder="إلى" 
               class="price-input" 
               onchange="applyFilters()">
        <span style="font-size:12px;color:#64748b;">ريال</span>
    </div>

    {{-- السطر الرابع: أزرار التحكم --}}
    <div class="filter-actions">
        <button onclick="resetFilters()" class="btn btn-reset">
            🔄 إعادة ضبط الفلاتر
        </button>
        <span class="filter-count">
            تم العثور على <strong>{{ count($products) }}</strong> منتج
        </span>
    </div>
</div>

    {{-- ✅ شبكة بطاقات المنتجات --}}
    <div class="products-grid">
        @forelse($products as $product)
        <div class="product-card" data-status="{{ $product->is_active }}">
            <div class="card-img">
                @if($product->base_image_url)
                    <img src="{{ $product->base_image_url }}" alt="{{ $product->product_name }}" loading="lazy">
                @else
                    <div class="img-placeholder"><span>📷</span><small>لا توجد صورة</small></div>
                @endif
                <div class="card-badges">
                    <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $product->is_active ? '✅ نشط' : '❌ غير نشط' }}
                    </span>
                    @if($product->brand_name)
                    <span class="brand-badge">🏷️ {{ $product->brand_name }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h3 class="product-name">{{ Str::limit($product->product_name, 40) }}</h3>
                @if($product->categories->isNotEmpty())
                <div class="category-chips">
                    @foreach($product->categories->take(3) as $cat)
                        <span class="chip">{{ $cat }}</span>
                    @endforeach
                </div>
                @endif
                <div class="price-row">
                    <div class="price-info">
                        @if($product->variant_count > 0)
                            <span class="price">{{ number_format($product->min_price, 2) }}</span>
                            @if($product->min_price != $product->max_price)
                                <span class="price-sep">-</span>
                                <span class="price">{{ number_format($product->max_price, 2) }}</span>
                            @endif
                            <span class="currency">ريال</span>
                        @else
                            <span class="no-price">بدون سعر</span>
                        @endif
                    </div>
                </div>
                <div class="card-actions">
                    <button onclick="editProduct({{ $product->product_id }})" class="btn-action btn-edit" type="button" title="تعديل">✏️</button>
                    <button onclick="deleteProduct({{ $product->product_id }})" class="btn-action btn-delete" type="button" title="حذف">🗑️</button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3>لا توجد منتجات</h3>
            <a href="{{ route('product.create') }}" class="btn btn-primary btn-lg">➕ إضافة أول منتج</a>
        </div>
        @endforelse
    </div>

    {{-- ✅ المودال --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal-container" style="max-width: 800px;">
            <div class="modal-header">
                <h3>✏️ تعديل المنتج</h3>
                <button class="modal-close" onclick="closeModal()" type="button">&times;</button>
            </div>
            <div class="modal-body" id="editModalBody">
                <div style="text-align:center;padding:40px;">⏳ جاري التحميل...</div>
            </div>
        </div>
    </div>

@endsection

{{-- ============================================ --}}
{{-- ✅ السكربت الكامل مع جميع التبويبات --}}
{{-- ============================================ --}}
<script>
// ========== دوال مساعدة ==========
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function closeModal() {
    document.getElementById('editModal')?.classList.remove('show');
}

// ========== حذف منتج ==========
function deleteProduct(id) {
    if (!confirm('🗑️ هل أنت متأكد من حذف هذا المنتج؟')) return;
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch(`/api/product/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.success ? '✅ ' + data.message : '❌ ' + data.message);
        if (data.success) location.reload();
    });
}

// ========== تبديل التبويبات ==========
function switchTab(evt, tabName) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    evt.target.classList.add('active');
    document.getElementById('tab-' + tabName)?.classList.add('active');
}

// ========== دوال المتغيرات ==========
let variantIndex = 100;

function addVariantRow(data = {}) {
    const i = variantIndex++;
    const container = document.getElementById('variantsContainer');
    const html = `
        <div class="variant-row" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:10px;">
            <input type="hidden" name="variants[${i}][variant_id]" value="${data.variant_id || ''}">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;">
                <div><label style="font-size:12px;">SKU *</label><input name="variants[${i}][SKU]" value="${escapeHtml(data.SKU || '')}" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">السعر *</label><input type="number" step="0.01" name="variants[${i}][price]" value="${data.price || ''}" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">الحجم</label><input name="variants[${i}][size_option]" value="${escapeHtml(data.size_option || '')}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">اللون</label><input name="variants[${i}][color_option]" value="${escapeHtml(data.color_option || '')}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">التغليف</label><input name="variants[${i}][packaging]" value="${escapeHtml(data.packaging || '')}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">الوزن (كجم)</label><input type="number" step="0.001" name="variants[${i}][weight_kg]" value="${data.weight_kg || ''}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
            </div>
            <button type="button" onclick="this.closest('.variant-row').remove()" style="margin-top:8px;background:#fee2e2;color:#dc2626;border:none;padding:6px 14px;border-radius:6px;cursor:pointer;">🗑️ حذف المتغير</button>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

// ========== دوال الموردين ==========
let supplierIndex = 200;

function addSupplierRow(suppliersList = [], data = {}) {
    const i = supplierIndex++;
    const container = document.getElementById('suppliersContainer');
    let opts = '<option value="">اختر المورد</option>';
    suppliersList.forEach(s => {
        const sel = s.supplier_id == data.supplier_id ? 'selected' : '';
        opts += `<option value="${s.supplier_id}" ${sel}>${s.company_name || s.name || ''}</option>`;
    });
    const html = `
        <div class="supplier-row" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:10px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;">
                <div><label style="font-size:12px;">المورد *</label><select name="suppliers[${i}][supplier_id]" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">${opts}</select></div>
                <div><label style="font-size:12px;">سعر التوريد *</label><input type="number" step="0.01" name="suppliers[${i}][supply_price]" value="${data.supply_price || ''}" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">مدة التوريد (أيام)</label><input type="number" name="suppliers[${i}][lead_time_days]" value="${data.lead_time_days || ''}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
                <div><label style="font-size:12px;">الحد الأدنى للطلب</label><input type="number" name="suppliers[${i}][minimum_order]" value="${data.minimum_order || 1}" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;"></div>
            </div>
            <button type="button" onclick="this.closest('.supplier-row').remove()" style="margin-top:8px;background:#fee2e2;color:#dc2626;border:none;padding:6px 14px;border-radius:6px;cursor:pointer;">🗑️ حذف المورد</button>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

// ========== الدالة الرئيسية للتعديل ==========
function editProduct(id) {
    console.log('✅ editProduct:', id);
    const modal = document.getElementById('editModal');
    if (!modal) return;
    modal.classList.add('show');
    
    document.getElementById('editModalBody').innerHTML = '<div style="text-align:center;padding:40px;">⏳ جاري تحميل جميع البيانات...</div>';
    
    fetch(`/api/product/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.message);
            buildFullEditForm(data);
        })
        .catch(error => {
            document.getElementById('editModalBody').innerHTML = `<div style="color:red;padding:20px;text-align:center;">❌ ${error.message}</div>`;
        });
}

// ========== بناء النموذج الكامل ==========
function buildFullEditForm(data) {
    const p = data.product;
    const brands = data.brands || [];
    const categories = data.categories || [];
    const variants = data.variants || [];
    const productCategories = data.product_categories || [];
    const productSuppliers = data.product_suppliers || [];
    const suppliers = data.suppliers || [];
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // بناء خيارات العلامات التجارية
    let brandOpts = '<option value="">بدون علامة</option>';
    brands.forEach(b => {
        brandOpts += `<option value="${b.brand_id}" ${b.brand_id == p.brand_id ? 'selected' : ''}>${b.name}</option>`;
    });
    
    // بناء خيارات التصنيفات
    let catCheckboxes = '';
    categories.forEach(c => {
        const checked = productCategories.includes(c.category_id) ? 'checked' : '';
        catCheckboxes += `
            <label style="display:flex;align-items:center;gap:8px;padding:8px;background:#f8fafc;border-radius:6px;cursor:pointer;">
                <input type="checkbox" name="categories[]" value="${c.category_id}" ${checked}> ${c.name}
            </label>`;
    });
    
    const html = `
        <form id="editForm" onsubmit="saveAllData(event, ${p.product_id})">
            <input type="hidden" name="_token" value="${token}">
            
            {{-- تبويبات --}}
            <div style="display:flex;gap:4px;background:#f1f5f9;padding:4px;border-radius:12px;margin-bottom:16px;flex-wrap:wrap;">
                <button type="button" class="tab-btn active" onclick="switchTab(event,'basic')" style="padding:8px 16px;border:none;background:white;border-radius:8px;cursor:pointer;font-weight:600;">📋 الأساسية</button>
                <button type="button" class="tab-btn" onclick="switchTab(event,'variants')" style="padding:8px 16px;border:none;background:transparent;border-radius:8px;cursor:pointer;">🔄 المتغيرات (${variants.length})</button>
                <button type="button" class="tab-btn" onclick="switchTab(event,'categories')" style="padding:8px 16px;border:none;background:transparent;border-radius:8px;cursor:pointer;">📂 التصنيفات (${productCategories.length})</button>
                <button type="button" class="tab-btn" onclick="switchTab(event,'suppliers')" style="padding:8px 16px;border:none;background:transparent;border-radius:8px;cursor:pointer;">🚚 الموردين (${productSuppliers.length})</button>
            </div>
            
            {{-- تبويب الأساسية --}}
            <div class="tab-panel active" id="tab-basic" style="display:grid;gap:15px;">
                <div><label style="font-weight:bold;">📝 اسم المنتج *</label><input type="text" name="product_name" value="${escapeHtml(p.product_name)}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div><label style="font-weight:bold;">🏷️ العلامة التجارية</label><select name="brand_id" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">${brandOpts}</select></div>
                    <div><label style="font-weight:bold;">📊 الحالة</label><select name="is_active" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"><option value="1" ${p.is_active==1?'selected':''}>✅ نشط</option><option value="0" ${p.is_active==0?'selected':''}>❌ غير نشط</option></select></div>
                </div>
                <div><label style="font-weight:bold;">📄 الوصف</label><textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">${escapeHtml(p.description||'')}</textarea></div>
                <div>
                    <label style="font-weight:bold;">🖼️ الصورة</label>
                    ${p.base_image_url ? `<div style="margin-bottom:8px;"><img src="${p.base_image_url}" style="max-width:150px;border-radius:8px;"></div>` : ''}
                    <input type="file" name="base_image_url" accept="image/*" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">
                    <small>اتركه فارغاً لعدم تغيير الصورة</small>
                </div>
            </div>
            
            {{-- تبويب المتغيرات --}}
            <div class="tab-panel" id="tab-variants" style="display:none;">
                <div id="variantsContainer"></div>
                <button type="button" onclick="addVariantRow()" style="width:100%;padding:10px;background:#e0e7ff;color:#4f46e5;border:2px dashed #4f46e5;border-radius:8px;cursor:pointer;font-weight:600;margin-top:10px;">➕ إضافة متغير جديد</button>
            </div>
            
            {{-- تبويب التصنيفات --}}
            <div class="tab-panel" id="tab-categories" style="display:none;">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:8px;">${catCheckboxes}</div>
            </div>
            
            {{-- تبويب الموردين --}}
            <div class="tab-panel" id="tab-suppliers" style="display:none;">
                <div id="suppliersContainer"></div>
                <button type="button" onclick="addSupplierRow(${JSON.stringify(suppliers).replace(/"/g, '&quot;')})" style="width:100%;padding:10px;background:#e0e7ff;color:#4f46e5;border:2px dashed #4f46e5;border-radius:8px;cursor:pointer;font-weight:600;margin-top:10px;">➕ إضافة مورد</button>
            </div>
            
            {{-- أزرار الحفظ --}}
            <div style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeModal()" style="padding:10px 20px;border:1px solid #ddd;border-radius:8px;background:white;cursor:pointer;">❌ إلغاء</button>
                <button type="submit" style="padding:10px 20px;background:#4F46E5;color:white;border:none;border-radius:8px;cursor:pointer;">💾 حفظ جميع التعديلات</button>
            </div>
        </form>`;
    
    document.getElementById('editModalBody').innerHTML = html;
    
    // إضافة المتغيرات الموجودة
    if (variants.length > 0) {
        variants.forEach(v => addVariantRow(v));
    }
    
    // إضافة الموردين الموجودين
    if (productSuppliers.length > 0) {
        productSuppliers.forEach(s => addSupplierRow(suppliers, s));
    }
}

// ========== حفظ جميع البيانات ==========
function saveAllData(e, productId) {
    e.preventDefault();
    
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    
    btn.disabled = true;
    btn.textContent = '⏳ جاري حفظ جميع البيانات...';
    
    fetch(`/api/product/${productId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
            btn.disabled = false;
            btn.textContent = '💾 حفظ جميع التعديلات';
        }
    })
    .catch(() => {
        alert('❌ خطأ في الحفظ');
        btn.disabled = false;
        btn.textContent = '💾 حفظ جميع التعديلات';
    });
}

// ========== بحث وفلترة ==========
function filterProducts() {
    const search = document.getElementById('searchInput')?.value.toLowerCase().trim() || '';
    const status = document.getElementById('statusFilter')?.value || '';
    document.querySelectorAll('.product-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        const cardStatus = String(card.dataset.status);
        card.style.display = (search === '' || text.includes(search)) && (status === '' || cardStatus === status) ? '' : 'none';
    });
}

// ========== تهيئة الأحداث ==========
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('refreshBtn')?.addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        filterProducts();
    });
    document.getElementById('searchInput')?.addEventListener('input', filterProducts);
    document.getElementById('statusFilter')?.addEventListener('change', filterProducts);
    
    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
});
// ========== فلترة متقدمة ==========

// تطبيق الفلاتر (إعادة تحميل الصفحة مع المعاملات)
function applyFilters() {
    const params = new URLSearchParams();
    
    const search = document.getElementById('searchInput')?.value;
    const sortBy = document.getElementById('sortBy')?.value;
    const categoryId = document.getElementById('categoryFilter')?.value;
    const brandId = document.getElementById('brandFilter')?.value;
    const isActive = document.getElementById('activeFilter')?.value;
    const stockStatus = document.getElementById('stockFilter')?.value;
    const priceFrom = document.getElementById('priceFrom')?.value;
    const priceTo = document.getElementById('priceTo')?.value;
    
    if (search) params.set('search', search);
    if (sortBy && sortBy !== 'newest') params.set('sort_by', sortBy);
    if (categoryId) params.set('category_id', categoryId);
    if (brandId) params.set('brand_id', brandId);
    if (isActive !== '') params.set('is_active', isActive);
    if (stockStatus) params.set('stock_status', stockStatus);
    if (priceFrom) params.set('price_from', priceFrom);
    if (priceTo) params.set('price_to', priceTo);
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// إعادة ضبط جميع الفلاتر
function resetFilters() {
    window.location.href = window.location.pathname;
}

// فلترة مباشرة بالبحث (بدون إعادة تحميل)
document.getElementById('searchInput')?.addEventListener('input', function() {
    filterProductsLive();
});

function filterProductsLive() {
    const search = document.getElementById('searchInput')?.value.toLowerCase().trim() || '';
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = search === '' || text.includes(search) ? '' : 'none';
    });
}

// تهيئة
document.addEventListener('DOMContentLoaded', () => {
    // يمكن إضافة أي تهيئة إضافية هنا
});
// ✅ دالة الحذف الصحيحة
function deleteProduct(id) {
    if (!confirm('🗑️ هل أنت متأكد من حذف هذا المنتج؟\n\nسيتم حذف جميع المتغيرات والتصنيفات والموردين المرتبطة به!')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('❌ خطأ: CSRF Token غير موجود');
        return;
    }
    
    // ✅ استخدام POST مع _method=DELETE
    fetch(`/api/product/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + (data.message || 'فشل الحذف'));
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        alert('❌ حدث خطأ أثناء الحذف');
    });
}
</script>
