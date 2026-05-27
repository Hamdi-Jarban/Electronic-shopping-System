@extends('layouts.header')

@section('title', 'إضافة منتج جديد')

@section('content')

<div class="page-header">
    <div>
        <h1>📦 إضافة منتج جديد</h1>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">🏠 الرئيسية</a>
            <span>›</span>
            <a href="{{ route('product.index') }}">المنتجات</a>
            <span>›</span>
            <span>إضافة جديد</span>
        </div>
    </div>
</div>

{{-- رسائل النجاح والخطأ --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    {{-- ========== التبويبات ========== --}}
    <div class="form-tabs">
        <button type="button" class="form-tab active" onclick="switchTab(event,'basic')">
            <span>📋</span> الأساسية
        </button>
        <button type="button" class="form-tab" onclick="switchTab(event,'variants')">
            <span>🔄</span> المتغيرات
        </button>
        <button type="button" class="form-tab" onclick="switchTab(event,'categories')">
            <span>📂</span> التصنيفات
        </button>
        <button type="button" class="form-tab" onclick="switchTab(event,'suppliers')">
            <span>🚚</span> الموردين
        </button>
        <button type="button" class="form-tab" onclick="switchTab(event,'inventory')">
            <span>📊</span> المخزون
        </button>
    </div>

    {{-- ========== تبويب 1: الأساسية ========== --}}
    <div class="tab-content active" id="tab-basic">
        <div class="form-card">
            <h3 class="card-title">📦 معلومات المنتج الأساسية</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label><span class="required">*</span> اسم المنتج</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" 
                           required maxlength="255" placeholder="أدخل اسم المنتج">
                    @error('product_name') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>العلامة التجارية</label>
                    <select name="brand_id">
                        <option value="">-- اختر العلامة التجارية --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->brand_id }}" {{ old('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full-width">
                    <label>الوصف</label>
                    <textarea name="description" rows="4" placeholder="أدخل وصف المنتج">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>الصورة الرئيسية</label>
                    <div class="image-upload">
                        <div class="image-preview" id="baseImagePreview">🖼️</div>
                        <input type="file" name="base_image_url" accept="image/*" 
                               onchange="previewImage(this, 'baseImagePreview')">
                    </div>
                </div>

                <div class="form-group">
                    <label>حالة المنتج</label>
                    <select name="is_active">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>❌ غير نشط</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== تبويب 2: المتغيرات ========== --}}
    <div class="tab-content" id="tab-variants">
        <div class="form-card">
            <h3 class="card-title">🔄 متغيرات المنتج (SKU، سعر، حجم، لون)</h3>
            <div id="variantsContainer">
                {{-- متغير افتراضي --}}
                <div class="repeat-item variant-item">
                    <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
                    <div class="form-grid col-3">
                        <div class="form-group">
                            <label><span class="required">*</span> SKU</label>
                            <input type="text" name="variants[0][SKU]" required placeholder="مثال: MILK-FULL-1L">
                        </div>
                        <div class="form-group">
                            <label><span class="required">*</span> السعر</label>
                            <input type="number" step="0.01" name="variants[0][price]" required placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label>الحجم</label>
                            <input type="text" name="variants[0][size_option]" placeholder="مثال: 1 لتر">
                        </div>
                        <div class="form-group">
                            <label>اللون</label>
                            <input type="text" name="variants[0][color_option]" placeholder="مثال: أبيض">
                        </div>
                        <div class="form-group">
                            <label>التغليف</label>
                            <input type="text" name="variants[0][packaging]" placeholder="مثال: كرتون">
                        </div>
                        <div class="form-group">
                            <label>الوزن (كجم)</label>
                            <input type="number" step="0.001" name="variants[0][weight_kg]" placeholder="0.000">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-add" onclick="addVariant()">➕ إضافة متغير آخر</button>
        </div>
    </div>

    {{-- ========== تبويب 3: التصنيفات ========== --}}
    <div class="tab-content" id="tab-categories">
        <div class="form-card">
            <h3 class="card-title">📂 تصنيفات المنتج</h3>
            <div class="checkbox-grid">
                @foreach($categories as $category)
                <label class="checkbox-card">
                    <input type="checkbox" name="categories[]" value="{{ $category->category_id }}"
                           {{ in_array($category->category_id, old('categories', [])) ? 'checked' : '' }}>
                    <span class="checkbox-label-text">{{ $category->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== تبويب 4: الموردين ========== --}}
    <div class="tab-content" id="tab-suppliers">
        <div class="form-card">
            <h3 class="card-title">🚚 موردي المنتج</h3>
            <div id="suppliersContainer">
                <div class="repeat-item supplier-item">
                    <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
                    <div class="form-grid col-2">
                        <div class="form-group">
                            <label><span class="required">*</span> المورد</label>
                            <select name="suppliers[0][supplier_id]" required>
                                <option value="">-- اختر المورد --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><span class="required">*</span> سعر التوريد</label>
                            <input type="number" step="0.01" name="suppliers[0][supply_price]" required placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label>مدة التوريد (أيام)</label>
                            <input type="number" name="suppliers[0][lead_time_days]" min="0" placeholder="مثال: 3">
                        </div>
                        <div class="form-group">
                            <label>الحد الأدنى للطلب</label>
                            <input type="number" name="suppliers[0][minimum_order]" value="1" min="1">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-add" onclick="addSupplier()">➕ إضافة مورد آخر</button>
        </div>
    </div>

    {{-- ========== تبويب 5: المخزون ========== --}}
    <div class="tab-content" id="tab-inventory">
        <div class="form-card">
            <h3 class="card-title">📊 المخزون الأولي (يتم ربطه مع المتغيرات لاحقاً)</h3>
            <div id="inventoryContainer">
                <div class="repeat-item inventory-item">
                    <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
                    <div class="form-grid col-2">
                        <div class="form-group">
                            <label><span class="required">*</span> المستودع</label>
                            <select name="inventory[0][warehouse_id]" required>
                                <option value="">-- اختر المستودع --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><span class="required">*</span> الكمية</label>
                            <input type="number" name="inventory[0][quantity]" value="0" required min="0">
                        </div>
                        <div class="form-group">
                            <label>حد إعادة الطلب</label>
                            <input type="number" name="inventory[0][reorder_level]" value="10" min="0">
                        </div>
                        <div class="form-group">
                            <label>كمية إعادة الطلب</label>
                            <input type="number" name="inventory[0][reorder_quantity]" value="50" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-add" onclick="addInventory()">➕ إضافة مستودع آخر</button>
        </div>
    </div>

    {{-- ========== أزرار الحفظ ========== --}}
    <div class="form-actions">
        <a href="{{ route('product.index') }}" class="btn btn-secondary">❌ إلغاء</a>
        <button type="submit" class="btn btn-primary">💾 حفظ المنتج</button>
    </div>
</form>

@endsection

{{-- ========== CSS ========== --}}
@push('styles')
<style>
    .form-tabs {
        display: flex;
        gap: 4px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .form-tab {
        padding: 10px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        transition: all 0.2s;
    }
    
    .form-tab.active {
        background: white;
        color: #4f46e5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .form-tab:hover:not(.active) {
        background: #e2e8f0;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    .form-grid.col-3 {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    
    .required {
        color: #ef4444;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .image-upload {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
    }
    
    .image-preview {
        font-size: 40px;
        margin-bottom: 8px;
    }
    
    .image-preview img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
    }
    
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
    }
    
    .checkbox-card {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .checkbox-card:hover {
        background: #e0e7ff;
        border-color: #c7d2fe;
    }
    
    .repeat-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        position: relative;
    }
    
    .remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-add {
        width: 100%;
        padding: 12px;
        background: #e0e7ff;
        color: #4f46e5;
        border: 2px dashed #4f46e5;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        background: #c7d2fe;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: #4f46e5;
        color: white;
    }
    
    .btn-primary:hover {
        background: #4338ca;
    }
    
    .btn-secondary {
        background: white;
        color: #64748b;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #f1f5f9;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .error {
        color: #ef4444;
        font-size: 12px;
    }
</style>
@endpush

{{-- ========== JavaScript ========== --}}
@push('scripts')
<script>
let variantIndex = 1;
let supplierIndex = 1;
let inventoryIndex = 1;

// تبديل التبويبات
function switchTab(evt, tabName) {
    document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    evt.target.classList.add('active');
    document.getElementById('tab-' + tabName)?.classList.add('active');
}

// إضافة متغير
function addVariant() {
    const container = document.getElementById('variantsContainer');
    const html = `
        <div class="repeat-item variant-item">
            <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
            <div class="form-grid col-3">
                <div class="form-group">
                    <label><span class="required">*</span> SKU</label>
                    <input type="text" name="variants[${variantIndex}][SKU]" required>
                </div>
                <div class="form-group">
                    <label><span class="required">*</span> السعر</label>
                    <input type="number" step="0.01" name="variants[${variantIndex}][price]" required>
                </div>
                <div class="form-group">
                    <label>الحجم</label>
                    <input type="text" name="variants[${variantIndex}][size_option]">
                </div>
                <div class="form-group">
                    <label>اللون</label>
                    <input type="text" name="variants[${variantIndex}][color_option]">
                </div>
                <div class="form-group">
                    <label>التغليف</label>
                    <input type="text" name="variants[${variantIndex}][packaging]">
                </div>
                <div class="form-group">
                    <label>الوزن (كجم)</label>
                    <input type="number" step="0.001" name="variants[${variantIndex}][weight_kg]">
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    variantIndex++;
}

// إضافة مورد
function addSupplier() {
    const container = document.getElementById('suppliersContainer');
    const suppliers = @json($suppliers);
    let opts = '<option value="">-- اختر المورد --</option>';
    suppliers.forEach(s => opts += `<option value="${s.supplier_id}">${s.company_name}</option>`);
    
    const html = `
        <div class="repeat-item supplier-item">
            <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
            <div class="form-grid col-2">
                <div class="form-group">
                    <label><span class="required">*</span> المورد</label>
                    <select name="suppliers[${supplierIndex}][supplier_id]" required>${opts}</select>
                </div>
                <div class="form-group">
                    <label><span class="required">*</span> سعر التوريد</label>
                    <input type="number" step="0.01" name="suppliers[${supplierIndex}][supply_price]" required>
                </div>
                <div class="form-group">
                    <label>مدة التوريد (أيام)</label>
                    <input type="number" name="suppliers[${supplierIndex}][lead_time_days]" min="0">
                </div>
                <div class="form-group">
                    <label>الحد الأدنى للطلب</label>
                    <input type="number" name="suppliers[${supplierIndex}][minimum_order]" value="1" min="1">
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    supplierIndex++;
}

// إضافة مستودع
function addInventory() {
    const container = document.getElementById('inventoryContainer');
    const warehouses = @json($warehouses);
    let opts = '<option value="">-- اختر المستودع --</option>';
    warehouses.forEach(w => opts += `<option value="${w.warehouse_id}">${w.name}</option>`);
    
    const html = `
        <div class="repeat-item inventory-item">
            <button type="button" class="remove-btn" onclick="removeItem(this)" title="حذف">×</button>
            <div class="form-grid col-2">
                <div class="form-group">
                    <label><span class="required">*</span> المستودع</label>
                    <select name="inventory[${inventoryIndex}][warehouse_id]" required>${opts}</select>
                </div>
                <div class="form-group">
                    <label><span class="required">*</span> الكمية</label>
                    <input type="number" name="inventory[${inventoryIndex}][quantity]" value="0" required min="0">
                </div>
                <div class="form-group">
                    <label>حد إعادة الطلب</label>
                    <input type="number" name="inventory[${inventoryIndex}][reorder_level]" value="10" min="0">
                </div>
                <div class="form-group">
                    <label>كمية إعادة الطلب</label>
                    <input type="number" name="inventory[${inventoryIndex}][reorder_quantity]" value="50" min="0">
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    inventoryIndex++;
}

// حذف عنصر
function removeItem(btn) {
    const items = btn.closest('.repeat-item').parentElement.children;
    if (items.length > 1) {
        btn.closest('.repeat-item').remove();
    }
}

// معاينة الصورة
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" alt="معاينة">`;
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '🖼️';
    }
}
</script>
@endpush