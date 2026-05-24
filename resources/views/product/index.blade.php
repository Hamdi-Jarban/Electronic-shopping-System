@extends('layouts.header')

@section('title', 'إدارة المنتجات')
@section('content')
@vite('resources/css/product-index.css')
   
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
            <span class="stat-bg">📦</span>
        </div>

        <div class="stat-card bg-green">
            <div class="stat-icon-box">
                <span class="stat-emoji">✅</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['active'] }}</span>
                <span class="stat-label">منتجات نشطة</span>
            </div>
            <span class="stat-bg">✅</span>
        </div>

        <div class="stat-card bg-orange">
            <div class="stat-icon-box">
                <span class="stat-emoji">📊</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['stock'] }}</span>
                <span class="stat-label">إجمالي المخزون</span>
            </div>
            <span class="stat-bg">📊</span>
        </div>

        <div class="stat-card bg-purple">
            <div class="stat-icon-box">
                <span class="stat-emoji">🏷️</span>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $stats['variants'] }}</span>
                <span class="stat-label">إجمالي المتغيرات</span>
            </div>
            <span class="stat-bg">🏷️</span>
        </div>
    </div>

    {{-- ✅ شريط البحث والفلترة --}}
    <div class="filter-bar">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="ابحث عن منتج..." class="search-input">
        </div>
        <select id="statusFilter" class="filter-select">
            <option value="">📋 كل الحالات</option>
            <option value="1">✅ نشط</option>
            <option value="0">❌ غير نشط</option>
        </select>
        <button id="refreshBtn" class="btn btn-outline" title="تحديث">
            🔄
        </button>
    </div>

    {{-- ✅ شبكة بطاقات المنتجات --}}
    <div class="products-grid">
        @forelse($products as $product)
        <div class="product-card" data-status="{{ $product->is_active }}">
            {{-- صورة المنتج --}}
            <div class="card-img">
                @if($product->base_image_url)
                    <img src="{{ asset($product->base_image_url) }}" alt="{{ $product->product_name }}" loading="lazy">
                @else
                    <div class="img-placeholder">📷</div>
                @endif
               
                {{-- شارة الحالة --}}
                <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                  
                </span>
               
                {{-- شارة العلامة التجارية --}}
                @if($product->brand_name)
                <span class="brand-badge">
                    {{ $product->brand_name }}
                </span>
                @endif
            </div>

            {{-- جسم البطاقة --}}
            <div class="card-body">
                <h3 class="product-name" title="{{ $product->product_name }}">
                    {{ $product->product_name }}
                </h3>
               
                @if($product->categories->isNotEmpty())
                <div class="category-chips">
                    @foreach($product->categories as $cat)
                        <span class="chip">{{ $cat }}</span>
                    @endforeach
                </div>
                @endif

                @if($product->description)
                <p class="product-desc">
                    {{ Str::limit($product->description, 60) }}
                </p>
                @endif

                {{-- السعر والمخزون --}}
                <div class="price-stock-row">
                    <div class="price-block">
                        @if($product->min_price == $product->max_price)
                            <span class="price">{{ number_format($product->min_price, 2) }}</span>
                        @else
                            <span class="price-from">{{ number_format($product->min_price, 2) }}</span>
                            <span class="price-sep">-</span>
                            <span class="price-to">{{ number_format($product->max_price, 2) }}</span>
                        @endif
                        <span class="currency">ريال</span>
                    </div>
                   
                    <span class="stock-badge {{ $product->total_stock > 10 ? 'stock-high' : ($product->total_stock > 0 ? 'stock-medium' : 'stock-zero') }}">
                        {{ $product->total_stock > 0 ? $product->total_stock . ' قطعة' : 'نفذ' }}
                    </span>
                </div>

                {{-- المتغيرات --}}
                @if($product->variant_count > 0)
                <div class="variants-info">
                    <span class="variant-icon">🔄</span>
                    <span class="variant-text">{{ $product->variant_count }} متغير</span>
                    <span class="variant-skus">({{ $product->sku_list }})</span>
                </div>
                @endif

                {{-- أزرار الإجراءات --}}
                <div class="card-actions">
                    <a href="{{ route('product.show', $product->product_id) }}" class="btn-action btn-view" title="عرض">
                        👁️
                    </a>
                    <button onclick="editProduct({{ $product->product_id }})" class="btn-action btn-edit" title="تعديل">
                        ✏️
                    </button>
                    <button onclick="deleteProduct({{ $product->product_id }})" class="btn-action btn-delete" title="حذف">
                        🗑️
                    </button>
                    <button class="btn-action btn-qr" title="QR Code">
                        📱
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <span class="empty-emoji">📭</span>
            <h3>لا توجد منتجات</h3>
            <p>ابدأ بإضافة منتجك الأول</p>
            <a href="{{ route('product.create') }}" class="btn btn-primary">➕ إضافة منتج</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(isset($products) && method_exists($products, 'links'))
    <div class="pagination-wrapper">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- ✅ نافذة التعديل المنبثقة (تم إغلاق الـ div هنا بشكل سليم) --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>✏️ تعديل المنتج</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="editModalBody">
            <div class="loading-spinner">جاري التحميل...</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('refreshBtn').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterProducts(); // إعادة تشغيل الفلترة لإظهار كل المنتجات مجدداً
});
// ============================================
// بحث وفلترة مباشرة
// ============================================

document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('statusFilter').addEventListener('change', filterProducts);
alert("yes");
function filterProducts() {
    // 1. جلب قيم الإدخال وتحويلها لنصوص نظيفة
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const status = document.getElementById('statusFilter').value; // قيمته ستكون "" أو "1" أو "0"
    const cards  = document.querySelectorAll('.product-card');
   
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const cardStatus = String(card.dataset.status); 
        const matchSearch = search === '' || text.includes(search);
        const matchStatus = status === '' || cardStatus === status;
        if (matchSearch && matchStatus) {
            card.style.display = ''; 
        } else {
            card.style.display = 'none';
        }
    });
}

// ============================================
// نافذة التعديل
// ============================================
function editProduct(id) {
    // 1. إظهار المودال أولاً لكي يشعر المستخدم بالاستجابة
    const modal = document.getElementById('editModal');
    if(modal) {
        modal.classList.add('show');
    } else {
        console.error("المودال غير موجود في صفحة HTML الحالية!");
        return;
    }
    
    document.getElementById('editModalBody').innerHTML = '<div class="loading-spinner">جاري التحميل...</div>';
   
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // 2. طلب البيانات من السيرفر
    fetch(`/api/product/${id}/edit`)
        .then(res => {
            if (!res.ok) throw new Error('فشل في جلب البيانات من السيرفر');
            return res.json();
        })
        .then(data => {
            // التحقق من أن البيانات وصلت بشكل صحيح لتجنب انهيار المتصفح
            const productName = data.product_name || '';
            const description = data.description || '';
            const brandId = data.brand_id || '';
            const isActive = data.is_active ?? 1;
            const brands = data.brands || [];

            document.getElementById('editModalBody').innerHTML = `
                <form id="editForm" onsubmit="updateProduct(event, ${id})">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>اسم المنتج</label>
                            <input type="text" name="product_name" value="${productName}" required>
                        </div>
                        <div class="form-group">
                            <label>العلامة التجارية</label>
                            <select name="brand_id">
                                <option value="">اختر</option>
                                ${brands.map(b => `<option value="${b.brand_id}" ${b.brand_id == brandId ? 'selected' : ''}>${b.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label>الوصف</label>
                            <textarea name="description" rows="3">${description}</textarea>
                        </div>
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="is_active">
                                <option value="1" ${isActive == 1 ? 'selected' : ''}>نشط</option>
                                <option value="0" ${isActive == 0 ? 'selected' : ''}>غير نشط</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeModal()">إلغاء</button>
                        <button type="submit" class="btn btn-primary">💾 حفظ</button>
                    </div>
                </form>
            `;
        })
        .catch(error => {
            console.error(error);
            document.getElementById('editModalBody').innerHTML = `
                <div style="color: red; padding: 20px; text-align: center;">
                    ❌ فشل تحميل البيانات. تأكد من تشغيل السيرفر ومن مسار الـ Route.
                </div>
            `;
        });
}
function closeModal() {
    document.getElementById('editModal').classList.remove('show');
}

function updateProduct(e, id) {
    e.preventDefault();
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
   
    fetch(`/api/product/${id}`, {
        method: 'POST',
        body: formData,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ تم التحديث');
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    });
}

// ============================================
// حذف منتج
// ============================================
function deleteProduct(id) {
    if (confirm('🗑️ هل أنت متأكد من حذف هذا المنتج؟')) {
        fetch(`/api/product/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ تم الحذف');
                location.reload();
            }
        });
    }
}

// إغلاق المودال بالنقر خارجه
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>