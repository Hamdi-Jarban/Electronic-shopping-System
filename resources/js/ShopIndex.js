document.addEventListener('DOMContentLoaded', () => {
    // ═══════════ DOM REFS ═══════════
    const productsGrid = document.getElementById('productsGrid');
    const emptyState = document.getElementById('emptyState');
    const paginationWrapper = document.getElementById('paginationWrapper');
    const resultsCount = document.getElementById('resultsCount');
    const activeFiltersContainer = document.getElementById('activeFilters');
    const searchInput = document.getElementById('productSearch');
    const sortSelect = document.getElementById('sortSelect');
    const filtersSidebar = document.getElementById('filtersSidebar');
    const filterBackdrop = document.getElementById('filterBackdrop');
    const shopToast = document.getElementById('shopToast');

    let allProducts = [...document.querySelectorAll('.product-card')];

    const state = {
    categories: [],
    brands: [],
    rating: null,
    minPrice: null,
    maxPrice: null,
    search: '',
    sort: 'newest',
    };

    // ═══════════ MOBILE FILTERS ═══════════
    document.getElementById('openFiltersBtn')?.addEventListener('click', () => {
    filtersSidebar.classList.add('open');
    filterBackdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
    });

    const closeMobileFilters = () => {
    filtersSidebar.classList.remove('open');
    filterBackdrop.classList.remove('open');
    document.body.style.overflow = '';
    };

    document.getElementById('closeFiltersBtn')?.addEventListener('click', closeMobileFilters);
    filterBackdrop?.addEventListener('click', closeMobileFilters);

    // ═══════════ TOAST ═══════════
    

    // ═══════════ RENDER ACTIVE TAGS ═══════════
    function renderActiveTags() {
    let html = '';
    state.categories.forEach(c => html += `<span class="filter-tag">${c}<span class="filter-tag-remove" data-type="category" data-value="${c}">&times;</span></span>`);
    state.brands.forEach(b => html += `<span class="filter-tag">${b}<span class="filter-tag-remove" data-type="brand" data-value="${b}">&times;</span></span>`);
    if (state.rating) html += `<span class="filter-tag">${state.rating} ★ فأكثر<span class="filter-tag-remove" data-type="rating">&times;</span></span>`;
    if (state.minPrice !== null || state.maxPrice !== null) {
    const range = `${state.minPrice ?? 0} - ${state.maxPrice ?? '∞'} ر.س`;
    html += `<span class="filter-tag">${range}<span class="filter-tag-remove" data-type="price">&times;</span></span>`;
    }
    activeFiltersContainer.innerHTML = html;

    // Attach remove events
    activeFiltersContainer.querySelectorAll('.filter-tag-remove').forEach(btn => {
    btn.addEventListener('click', () => {
    const type = btn.dataset.type;
    const value = btn.dataset.value;
    if (type === 'category') {
    state.categories = state.categories.filter(c => c !== value);
    document.querySelector(`[data-filter="category"][value="${value}"]`).checked = false;
    } else if (type === 'brand') {
    state.brands = state.brands.filter(b => b !== value);
    document.querySelector(`[data-filter="brand"][value="${value}"]`).checked = false;
    } else if (type === 'rating') {
    state.rating = null;
    document.querySelectorAll('[data-filter="rating"]').forEach(r => r.checked = false);
    } else if (type === 'price') {
    state.minPrice = null;
    state.maxPrice = null;
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    }
    applyFilters();
    });
    });
    }

    // ═══════════ APPLY FILTERS ═══════════
    function applyFilters() {
    let visible = [...allProducts];

    if (state.categories.length > 0) {
    visible = visible.filter(p => state.categories.includes(p.dataset.category));
    }
    if (state.brands.length > 0) {
    visible = visible.filter(p => state.brands.includes(p.dataset.brand));
    }
    if (state.rating) {
    visible = visible.filter(p => parseInt(p.dataset.rating) >= parseInt(state.rating));
    }
    if (state.minPrice !== null) {
    visible = visible.filter(p => parseFloat(p.dataset.price) >= state.minPrice);
    }
    if (state.maxPrice !== null) {
    visible = visible.filter(p => parseFloat(p.dataset.price) <= state.maxPrice);
    }
    if (state.search) {
    const q = state.search.toLowerCase();
    visible = visible.filter(p =>
    p.dataset.name.toLowerCase().includes(q) ||
    p.dataset.desc.toLowerCase().includes(q) ||
    p.dataset.brand.toLowerCase().includes(q) ||
    p.dataset.category.toLowerCase().includes(q)
    );
    }

    // Sort
    switch(state.sort) {
    case 'price-asc':
    visible.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
    break;
    case 'price-desc':
    visible.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
    break;
    case 'rating':
    visible.sort((a, b) => parseInt(b.dataset.rating) - parseInt(a.dataset.rating));
    break;
    case 'bestseller':
    visible.sort((a, b) => (b.dataset.bestseller === 'true' ? 1 : 0) - (a.dataset.bestseller === 'true' ? 1 : 0));
    break;
    case 'newest':
    default:
    visible.sort((a, b) => parseInt(b.dataset.id) - parseInt(a.dataset.id));
    break;
    }

    productsGrid.innerHTML = '';
    if (visible.length === 0) {
    productsGrid.style.display = 'none';
    emptyState.style.display = 'block';
    paginationWrapper.style.display = 'none';
    } else {
    productsGrid.style.display = 'grid';
    emptyState.style.display = 'none';
    paginationWrapper.style.display = 'flex';
    visible.forEach(p => productsGrid.appendChild(p));
    }

    resultsCount.innerHTML = `عرض <strong>${visible.length}</strong> منتج`;
    renderActiveTags();
    }

    // ═══════════ EVENT LISTENERS ═══════════

    // Category checkboxes
    document.querySelectorAll('[data-filter="category"]').forEach(cb => {
    cb.addEventListener('change', () => {
    state.categories = [...document.querySelectorAll('[data-filter="category"]:checked')].map(c => c.value);
    applyFilters();
    });
    });

    // Brand checkboxes
    document.querySelectorAll('[data-filter="brand"]').forEach(cb => {
    cb.addEventListener('change', () => {
    state.brands = [...document.querySelectorAll('[data-filter="brand"]:checked')].map(c => c.value);
    applyFilters();
    });
    });

    // Rating radios
    document.querySelectorAll('[data-filter="rating"]').forEach(rb => {
    rb.addEventListener('change', () => {
    state.rating = document.querySelector('[data-filter="rating"]:checked')?.value || null;
    applyFilters();
    });
    });

    // Price
    document.getElementById('applyPrice')?.addEventListener('click', () => {
    const min = document.getElementById('minPrice').value;
    const max = document.getElementById('maxPrice').value;
    state.minPrice = min ? parseFloat(min) : null;
    state.maxPrice = max ? parseFloat(max) : null;
    applyFilters();
    });

    // Search
    searchInput?.addEventListener('input', debounce(() => {
    state.search = searchInput.value;
    applyFilters();
    }, 250));

    // Sort
    sortSelect?.addEventListener('change', () => {
    state.sort = sortSelect.value;
    applyFilters();
    });

    // Reset all
    document.getElementById('resetAllFilters')?.addEventListener('click', resetAllFilters);

    function resetAllFilters() {
    state.categories = [];
    state.brands = [];
    state.rating = null;
    state.minPrice = null;
    state.maxPrice = null;
    state.search = '';
    state.sort = 'newest';
    if (searchInput) searchInput.value = '';
    if (sortSelect) sortSelect.value = 'newest';
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.querySelectorAll('[data-filter="category"],[data-filter="brand"]').forEach(c => c.checked = false);
    document.querySelectorAll('[data-filter="rating"]').forEach(r => r.checked = false);
    applyFilters();
    closeMobileFilters();
    }

    function debounce(fn, delay) {
    let timer;
    return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
    };
    }

    // Initial render
    renderActiveTags();
    });

    // ═══════════ GLOBAL FUNCTIONS ═══════════
    function toggleWishlist(btn) {
    btn.classList.toggle('liked');
    if (btn.closest('.quick-actions')) {
    btn.classList.toggle('liked');
    const svg = btn.querySelector('svg');
    if (btn.classList.contains('liked')) {
    svg.setAttribute('fill', 'currentColor');
    } else {
    svg.setAttribute('fill', 'none');
    }
    }
    }

    function addToCart(btn, productName) {
    if (btn.classList.contains('added')) return;
    btn.classList.add('added');
    btn.innerHTML = '✓ تمت الإضافة';

    const badge = document.querySelector('.cart-badge');
    if (badge) badge.textContent = (parseInt(badge.textContent || 0) + 1);

    const toast = document.getElementById('shopToast');
    if (toast) {
    toast.textContent = `✓ تم إضافة "${productName}" إلى السلة`;
    toast.classList.add('show');
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.remove('show'), 2200);
    }

    setTimeout(() => {
    btn.classList.remove('added');
    btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg> أضف للسلة';
    }, 2000);
    }
  