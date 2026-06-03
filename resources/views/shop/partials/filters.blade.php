{{-- الفلاتر الجانبية --}}
<div class="filter-sidebar-modern">
    <div class="filter-header">
        <h4><i class="bi bi-funnel"></i> تصفية النتائج</h4>
        <a href="{{ route('shop.index') }}" class="filter-clear">مسح الكل</a>
    </div>

    <form action="{{ route('shop.index') }}" method="GET" id="filterForm">
        {{-- بحث --}}
        <div class="filter-group">
            <label>🔍 بحث</label>
            <input type="text" name="search" class="form-control" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
        </div>

        {{-- الأقسام --}}
        <div class="filter-group">
            <label>📂 القسم</label>
            <div class="filter-checkboxes">
                @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" 
                               value="{{ $category->category_id }}" 
                               id="cat{{ $category->category_id }}"
                               {{ in_array($category->category_id, (array)request('categories', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cat{{ $category->category_id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- العلامة التجارية --}}
        <div class="filter-group">
            <label>🏷️ العلامة التجارية</label>
            <select name="brand" class="form-select">
                <option value="">الكل</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->brand_id }}" {{ request('brand') == $brand->brand_id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- نطاق السعر --}}
        <div class="filter-group">
            <label>💰 نطاق السعر</label>
            <div class="price-range-inputs">
                <input type="number" name="price_min" class="form-control" placeholder="من" value="{{ request('price_min') }}">
                <span>-</span>
                <input type="number" name="price_max" class="form-control" placeholder="إلى" value="{{ request('price_max') }}">
            </div>
        </div>

        {{-- الترتيب --}}
        <div class="filter-group">
            <label>📊 ترتيب حسب</label>
            <select name="sort" class="form-select">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر مبيعاً</option>
            </select>
        </div>

        {{-- أزرار --}}
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary w-100 mb-2">
                <i class="bi bi-check-lg"></i> تطبيق
            </button>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-lg"></i> مسح الفلاتر
            </a>
        </div>
    </form>
</div>