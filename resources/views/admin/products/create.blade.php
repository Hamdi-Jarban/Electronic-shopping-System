@extends('layouts.master')

@section('title', 'إضافة منتج')
@vite('resources/css/Admin/Product/Create.css')

@section('content')
<nav class="admin-navbar">
  <div class="nav-brand">
    <span class="brand-icon">📦</span>
    <span>لوحة تحكم المتجر</span>
  </div>
  <div class="breadcrumb">
    <a href="{{ route('admin.index') }}">الرئيسية</a>
    <span class="separator">›</span>
    <a href="{{ route('admin.products.index') }}">المنتجات</a>
    <span class="separator">›</span>
    <span class="current">إضافة منتج جديد</span>
  </div>
  <div class="nav-actions">
    <span style="font-size:var(--font-size-sm);color:var(--text-secondary);">مرحباً، {{ auth()->user()->name ?? 'أحمد' }}</span>
    <span class="nav-avatar">{{ mb_substr(auth()->user()->name ?? 'أ', 0, 1) }}</span>
  </div>
</nav>

<div class="app-layout">

  {{-- ========== MAIN FORM AREA ========== --}}
  <div class="preview-sidebar">
    <div class="preview-panel">
      <div class="preview-header">
        👁️ معاينة المنتج
      </div>
      <div class="preview-image-area">
        <span class="preview-img-icon">👕</span>
      </div>
      <div class="preview-details">
        <span class="preview-brand">ماركة الأصالة</span>
        <h3 class="preview-product-name">تيشيرت قطني فاخر - قصة كلاسيكية</h3>
        <div class="preview-price-row">
          <span class="preview-price">89.00 ر.س</span>
          <span class="preview-compare-price">140.00 ر.س</span>
          <span class="preview-discount-badge">خصم حتى ٣٦٪</span>
        </div>
        <div class="preview-categories">
          <span class="preview-category-tag">ملابس رجالية</span>
          <span class="preview-category-tag">تيشيرتات</span>
          <span class="preview-category-tag">ملابس صيفية</span>
        </div>
        <div class="preview-stock-indicator">
          <span class="stock-dot"></span> <span>متوفر في المخزون (٣ متغيرات)</span>
        </div>
        <p class="preview-summary">
          تيشيرت قطني فاخر بقصة كلاسيكية أنيقة، مصنوع من قطن مصري طويل التيلة بنسبة 100%، مثالي للارتداء اليومي...
        </p>
      </div>
    </div>
    <div class="card" style="box-shadow:var(--shadow-sm);">
      <div class="card-body compact">
        <span style="font-weight:var(--font-weight-semibold);font-size:var(--font-size-sm);">📊 إحصائيات سريعة</span>
        <hr class="section-divider">
        <div style="display:flex;flex-direction:column;gap:var(--spacing-2);font-size:var(--font-size-sm);">
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-secondary);">عدد المتغيرات:</span><span style="font-weight:var(--font-weight-semibold);">٣</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-secondary);">إجمالي المخزون:</span><span style="font-weight:var(--font-weight-semibold);">٤٣٥ قطعة</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-secondary);">نطاق السعر:</span><span style="font-weight:var(--font-weight-semibold);">٨٩ - ١٠٩ ر.س</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-secondary);">عدد الصور:</span><span style="font-weight:var(--font-weight-semibold);">٥ صور</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <form id="product-form" class="main-form-area"
    action="{{ route('admin.products.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    {{-- PAGE HEADER --}}
    <div class="page-header">
      <div class="page-title-group">
        <h1>إضافة منتج جديد</h1>
        <p class="page-subtitle">
          قم بإدخال بيانات المنتج الأساسية والمتغيرات والصور
        </p>
      </div>
      <span class="toggle-status status-active">● نشط</span>
    </div>

    {{-- ═══ SECTION 1: PRODUCT INFORMATION ═══ --}}
    <div class="card">
      <div class="card-header">
        <span class="card-title">
          <span class="card-icon icon-info">📋</span>
          معلومات المنتج الأساسية
        </span>
        <span class="text-muted">الحقول المميزة بـ <span style="color:var(--danger);">*</span> مطلوبة</span>
      </div>
      <div class="card-body">

        {{-- Product Name --}}
        <div class="form-group">
          <label class="form-label" for="productName">
            اسم المنتج <span class="required-star">*</span>
          </label>
          <input type="text" id="productName" name="name"
          class="form-control @error('name') is-invalid @enderror"
          placeholder="أدخل اسم المنتج الكامل" maxlength="255">
          @error('name')<span class="server-error">{{ $message }}</span>@enderror
          <span class="form-hint">سيظهر هذا الاسم للعملاء في صفحات المنتج ونتائج البحث</span>
        </div>

        {{-- Brand + Active Status --}}
        <div style="display:flex;gap:var(--spacing-5);flex-wrap:wrap;">
          <div class="form-group" style="flex:1;min-width:200px;">
            <label class="form-label" for="brandSelect">العلامة التجارية</label>
            <select id="brandSelect" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
              <option value="">اختر العلامة التجارية...</option>
              @foreach($brands as $brand)
              <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
              </option>
              @endforeach
            </select>
            @error('brand_id')<span class="server-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group" style="flex:0 0 auto;min-width:160px;">
            <label class="form-label">حالة المنتج</label>
            <div class="toggle-switch-wrapper">
              <label class="toggle-switch" for="activeToggle" aria-label="تبديل حالة التفعيل">
                <input type="checkbox" id="activeToggle" checked disabled>
                <span class="toggle-slider"></span>
              </label>
              <label class="toggle-label-text" for="activeToggle">نشط</label>
            </div>
            <span class="form-hint">سيتم تفعيل المنتج تلقائياً عند الحفظ</span>
          </div>
        </div>

        {{-- Categories Multi Select --}}
        <div class="form-group">
          <label class="form-label">
            التصنيفات <span class="required-star">*</span>
            <span class="optional-tag">(يمكن اختيار أكثر من تصنيف)</span>
          </label>
          <div class="category-checkbox-group @error('category_ids') is-invalid @enderror">
            @foreach($categories as $category)
            <label class="category-chip">
              <input type="checkbox" name="category_ids[]"
              value="{{ $category->id }}"
              {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
              {{ $category->name }}
            </label>
            @endforeach
          </div>
          @error('category_ids')<span class="server-error">{{ $message }}</span>@enderror
          <span class="form-hint">اختر التصنيفات المناسبة ليسهل على العملاء إيجاد منتجك</span>
        </div>

        {{-- Summary --}}
        <div class="form-group">
          <label class="form-label" for="productSummary">ملخص المنتج</label>
          <textarea id="productSummary" name="summary"
            class="form-control @error('summary') is-invalid @enderror"
            rows="2" maxlength="500"
            placeholder="ملخص مختصر يظهر في بطاقات المنتج ونتائج البحث...">{{ old('summary', 'تيشيرت قطني فاخر بقصة كلاسيكية أنيقة، مصنوع من قطن مصري طويل التيلة بنسبة 100%، مثالي للارتداء اليومي والإطلالات غير الرسمية.') }}</textarea>
          @error('summary')<span class="server-error">{{ $message }}</span>@enderror
          <span class="form-hint">وصف مختصر لا يتجاوز 500 حرف، يظهر في معاينات المنتج</span>
        </div>

        {{-- Description --}}
        <div class="form-group">
          <label class="form-label" for="productDescription">وصف المنتج الكامل</label>
          <textarea id="productDescription" name="description"
            class="form-control tall @error('description') is-invalid @enderror"
            rows="6" placeholder="ادخل وصف المنتج ">       </textarea>
          @error('description')<span class="server-error">{{ $message }}</span>@enderror
        </div>
      </div>
    </div>

    {{-- ═══ SECTION 2: VARIANTS ═══ --}}
    <div class="card">
      <div class="card-header">
        <span class="card-title">
          <span class="card-icon icon-variant">📐</span>
          متغيرات المنتج
        </span>
        <span class="text-muted">يجب إضافة متغير واحد على الأقل</span>
      </div>
      <div class="card-body">
        <div class="variant-cards-list">

          {{-- Variant 1 --}}
          <div class="variant-card">
            <div class="variant-card-header">
              <span class="variant-badge"><span class="variant-number">١</span> متغير: مقاس S - لون أبيض</span>
              <button type="button" class="variant-remove-btn" title="حذف المتغير" disabled>×</button>
            </div>
            <div class="variant-grid three-col">
              <div class="form-group">
                <label class="form-label">السعر (ر.س) <span class="required-star">*</span></label>
                <input type="number" name="variants[0][price]" class="form-control" value="{{ old('variants.0.price', '89') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">السعر قبل الخصم (ر.س)</label>
                <input type="number" name="variants[0][compare_at_price]" class="form-control" value="{{ old('variants.0.compare_at_price', '120') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">المستودع <span class="required-star">*</span></label>
                <select name="variants[0][warehouse_id]" class="form-control">
                  <option value="">اختر المستودع...</option>
                  @foreach($warehouses as $warehouse)
                  <option value="{{ $warehouse->id }}" {{ old('variants.0.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="variant-grid">
              <div class="form-group">
                <label class="form-label">الكمية في المخزون</label>
                <input type="number" name="variants[0][physical_qty]" class="form-control" value="{{ old('variants.0.physical_qty', '150') }}" min="0" placeholder="0">
              </div>
              <div class="form-group">
                <label class="form-label">حد التنبيه للمخزون المنخفض</label>
                <input type="number" name="variants[0][low_stock_threshold]" class="form-control" value="{{ old('variants.0.low_stock_threshold', '10') }}" min="0" placeholder="5">
              </div>
            </div>
            <div class="variant-attributes-row">
              <span style="font-size:var(--font-size-xs);color:var(--text-subdued);font-weight:var(--font-weight-medium);">الخصائص:</span>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="اللون" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[0][attributes][اللون]" value="{{ old('variants.0.attributes.اللون', 'أبيض ناصع') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="المقاس" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[0][attributes][المقاس]" value="{{ old('variants.0.attributes.المقاس', 'S') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="النمط" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[0][attributes][النمط]" value="{{ old('variants.0.attributes.النمط', 'سادة') }}" placeholder="القيمة">
              </div>
            </div>
          </div>

          {{-- Variant 2 --}}
          <div class="variant-card">
            <div class="variant-card-header">
              <span class="variant-badge"><span class="variant-number">٢</span> متغير: مقاس M - لون أبيض</span>
              <button type="button" class="variant-remove-btn" title="حذف المتغير" disabled>×</button>
            </div>
            <div class="variant-grid three-col">
              <div class="form-group">
                <label class="form-label">السعر (ر.س) <span class="required-star">*</span></label>
                <input type="number" name="variants[1][price]" class="form-control" value="{{ old('variants.1.price', '99') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">السعر قبل الخصم (ر.س)</label>
                <input type="number" name="variants[1][compare_at_price]" class="form-control" value="{{ old('variants.1.compare_at_price', '130') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">المستودع <span class="required-star">*</span></label>
                <select name="variants[1][warehouse_id]" class="form-control">
                  <option value="">اختر المستودع...</option>
                  @foreach($warehouses as $warehouse)
                  <option value="{{ $warehouse->id }}" {{ old('variants.1.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="variant-grid">
              <div class="form-group">
                <label class="form-label">الكمية في المخزون</label>
                <input type="number" name="variants[1][physical_qty]" class="form-control" value="{{ old('variants.1.physical_qty', '200') }}" min="0" placeholder="0">
              </div>
              <div class="form-group">
                <label class="form-label">حد التنبيه للمخزون المنخفض</label>
                <input type="number" name="variants[1][low_stock_threshold]" class="form-control" value="{{ old('variants.1.low_stock_threshold', '15') }}" min="0" placeholder="5">
              </div>
            </div>
            <div class="variant-attributes-row">
              <span style="font-size:var(--font-size-xs);color:var(--text-subdued);font-weight:var(--font-weight-medium);">الخصائص:</span>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="اللون" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[1][attributes][اللون]" value="{{ old('variants.1.attributes.اللون', 'أبيض ناصع') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="المقاس" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[1][attributes][المقاس]" value="{{ old('variants.1.attributes.المقاس', 'M') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="النمط" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[1][attributes][النمط]" value="{{ old('variants.1.attributes.النمط', 'سادة') }}" placeholder="القيمة">
              </div>
            </div>
          </div>

          {{-- Variant 3 --}}
          <div class="variant-card">
            <div class="variant-card-header">
              <span class="variant-badge"><span class="variant-number">٣</span> متغير: مقاس L - لون أسود</span>
              <button type="button" class="variant-remove-btn" title="حذف المتغير" disabled>×</button>
            </div>
            <div class="variant-grid three-col">
              <div class="form-group">
                <label class="form-label">السعر (ر.س) <span class="required-star">*</span></label>
                <input type="number" name="variants[2][price]" class="form-control" value="{{ old('variants.2.price', '109') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">السعر قبل الخصم (ر.س)</label>
                <input type="number" name="variants[2][compare_at_price]" class="form-control" value="{{ old('variants.2.compare_at_price', '140') }}" step="0.01" min="0" placeholder="0.00">
              </div>
              <div class="form-group">
                <label class="form-label">المستودع <span class="required-star">*</span></label>
                <select name="variants[2][warehouse_id]" class="form-control">
                  <option value="">اختر المستودع...</option>
                  @foreach($warehouses as $warehouse)
                  <option value="{{ $warehouse->id }}" {{ old('variants.2.warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="variant-grid">
              <div class="form-group">
                <label class="form-label">الكمية في المخزون</label>
                <input type="number" name="variants[2][physical_qty]" class="form-control" value="{{ old('variants.2.physical_qty', '85') }}" min="0" placeholder="0">
              </div>
              <div class="form-group">
                <label class="form-label">حد التنبيه للمخزون المنخفض</label>
                <input type="number" name="variants[2][low_stock_threshold]" class="form-control" value="{{ old('variants.2.low_stock_threshold', '8') }}" min="0" placeholder="5">
              </div>
            </div>
            <div class="variant-attributes-row">
              <span style="font-size:var(--font-size-xs);color:var(--text-subdued);font-weight:var(--font-weight-medium);">الخصائص:</span>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="اللون" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[2][attributes][اللون]" value="{{ old('variants.2.attributes.اللون', 'أسود كلاسيكي') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="المقاس" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[2][attributes][المقاس]" value="{{ old('variants.2.attributes.المقاس', 'L') }}" placeholder="القيمة">
              </div>
              <div class="variant-attr-pair">
                <input type="text" class="attr-key" value="النمط" readonly disabled>
                <span class="attr-separator">:</span>
                <input type="text" class="attr-value" name="variants[2][attributes][النمط]" value="{{ old('variants.2.attributes.النمط', 'سادة') }}" placeholder="القيمة">
              </div>
            </div>
          </div>

        </div>

        @error('variants')<span class="server-error">{{ $message }}</span>@enderror

        <button type="button" class="add-variant-btn" style="margin-top:var(--spacing-5);" disabled>
          <span class="plus-icon">+</span> إضافة متغير جديد
        </button>
        <span class="form-hint" style="text-align:center;display:block;">* لإضافة متغيرات جديدة ديناميكياً، يلزم تفعيل JavaScript</span>
      </div>
    </div>

    {{-- ═══ SECTION 3: IMAGES ═══ --}}
    <div class="card">
      <div class="card-header">
        <span class="card-title">
          <span class="card-icon icon-image">🖼️</span>
          صور المنتج
        </span>
        <span class="text-muted">يجب رفع صورة واحدة على الأقل</span>
      </div>
      <div class="card-body">
        <div class="image-upload-grid">

          {{-- Image 1 --}}
          <div class="image-card featured-card img-placeholder-bg-1">
            <span class="featured-badge">⭐ رئيسية</span>
            <span class="sort-badge">١</span>
            <span class="image-preview-placeholder">👕</span>
            <div class="variant-select-tag">
              <select name="images[0][variant_index]">
                <option value="">غير مرتبط بمتغير</option>
                <option value="0" selected>متغير ١ - مقاس S أبيض</option>
                <option value="1">متغير ٢ - مقاس M أبيض</option>
                <option value="2">متغير ٣ - مقاس L أسود</option>
              </select>
            </div>
            <input type="file" name="images[0][file]" class="file-input-hidden" accept="image/jpeg,image/png,image/webp" required>
            <input type="checkbox" name="images[0][is_featured]" value="1" class="featured-checkbox" checked aria-label="تعيين كصورة رئيسية">
            <span class="featured-label">رئيسية</span>
            <input type="hidden" name="images[0][sort_order]" value="1">
          </div>

          {{-- Image 2 --}}
          <div class="image-card img-placeholder-bg-2">
            <span class="featured-badge">⭐ رئيسية</span>
            <span class="sort-badge">٢</span>
            <span class="image-preview-placeholder">👕</span>
            <div class="variant-select-tag">
              <select name="images[1][variant_index]">
                <option value="">غير مرتبط بمتغير</option>
                <option value="0">متغير ١ - مقاس S أبيض</option>
                <option value="1" selected>متغير ٢ - مقاس M أبيض</option>
                <option value="2">متغير ٣ - مقاس L أسود</option>
              </select>
            </div>
            <input type="file" name="images[1][file]" class="file-input-hidden" accept="image/jpeg,image/png,image/webp" required>
            <input type="checkbox" name="images[1][is_featured]" value="1" class="featured-checkbox" aria-label="تعيين كصورة رئيسية">
            <span class="featured-label">رئيسية</span>
            <input type="hidden" name="images[1][sort_order]" value="2">
          </div>

          {{-- Image 3 --}}
          <div class="image-card img-placeholder-bg-3">
            <span class="featured-badge">⭐ رئيسية</span>
            <span class="sort-badge">٣</span>
            <span class="image-preview-placeholder">👕</span>
            <div class="variant-select-tag">
              <select name="images[2][variant_index]">
                <option value="">غير مرتبط بمتغير</option>
                <option value="0">متغير ١ - مقاس S أبيض</option>
                <option value="1">متغير ٢ - مقاس M أبيض</option>
                <option value="2" selected>متغير ٣ - مقاس L أسود</option>
              </select>
            </div>
            <input type="file" name="images[2][file]" class="file-input-hidden" accept="image/jpeg,image/png,image/webp" required>
            <input type="checkbox" name="images[2][is_featured]" value="1" class="featured-checkbox" aria-label="تعيين كصورة رئيسية">
            <span class="featured-label">رئيسية</span>
            <input type="hidden" name="images[2][sort_order]" value="3">
          </div>

          {{-- Image 4 --}}
          <div class="image-card img-placeholder-bg-4">
            <span class="featured-badge">⭐ رئيسية</span>
            <span class="sort-badge">٤</span>
            <span class="image-preview-placeholder">🔍</span>
            <div class="variant-select-tag">
              <select name="images[3][variant_index]">
                <option value="" selected>غير مرتبط بمتغير</option>
                <option value="0">متغير ١ - مقاس S أبيض</option>
                <option value="1">متغير ٢ - مقاس M أبيض</option>
                <option value="2">متغير ٣ - مقاس L أسود</option>
              </select>
            </div>
            <input type="file" name="images[3][file]" class="file-input-hidden" accept="image/jpeg,image/png,image/webp">
            <input type="checkbox" name="images[3][is_featured]" value="1" class="featured-checkbox" aria-label="تعيين كصورة رئيسية">
            <span class="featured-label">رئيسية</span>
            <input type="hidden" name="images[3][sort_order]" value="4">
          </div>

          {{-- Image 5 --}}
          <div class="image-card img-placeholder-bg-5">
            <span class="featured-badge">⭐ رئيسية</span>
            <span class="sort-badge">٥</span>
            <span class="image-preview-placeholder">🧵</span>
            <div class="variant-select-tag">
              <select name="images[4][variant_index]">
                <option value="" selected>غير مرتبط بمتغير</option>
                <option value="0">متغير ١ - مقاس S أبيض</option>
                <option value="1">متغير ٢ - مقاس M أبيض</option>
                <option value="2">متغير ٣ - مقاس L أسود</option>
              </select>
            </div>
            <input type="file" name="images[4][file]" class="file-input-hidden" accept="image/jpeg,image/png,image/webp">
            <input type="checkbox" name="images[4][is_featured]" value="1" class="featured-checkbox" aria-label="تعيين كصورة رئيسية">
            <span class="featured-label">رئيسية</span>
            <input type="hidden" name="images[4][sort_order]" value="5">
          </div>

          {{-- Add More Card (UI only) --}}
          <div class="image-card add-more-card" role="button" aria-label="إضافة صورة جديدة" tabindex="0">
            <span class="add-icon">＋</span>
            <span class="add-text">رفع صورة جديدة</span>
          </div>
        </div>
        @error('images')<span class="server-error">{{ $message }}</span>@enderror
        <p class="form-hint" style="margin-top:var(--spacing-4);">
          🖼️ الصيغ المدعومة: JPG, PNG, WebP | الحد الأقصى للحجم: 2 ميجابايت | الأبعاد الموصى بها: 1200×1200 بكسل
          <br>⚠️ يُرجى اختيار صورة رئيسية واحدة فقط عن طريق تحديد خانة الاختيار ⭐ أسفل كل صورة
        </p>
      </div>
    </div>

  </form>

  {{-- ═══ PREVIEW SIDEBAR ═══ --}}

</div>

{{-- ═══ ACTION BUTTONS BAR ═══ --}}
<div class="action-bar">
  <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">إلغاء</a>
  <button type="submit" class="btn btn-secondary" form="product-form" formaction="{{ route('admin.products.store') }}" name="draft" value="1">
    💾 حفظ كمسودة
  </button>
  <button type="submit" class="btn btn-primary btn-lg" form="product-form">
    ✅ حفظ المنتج
  </button>
</div>
@endsection;