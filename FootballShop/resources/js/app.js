import './bootstrap';

window.toggleMobileMenu = function () {
    const menu = document.querySelector('.mobile-menu');
    if (menu) {
        menu.classList.toggle('show');
    }
};

window.toggleSearchBar = function () {
    const searchForm = document.getElementById('search-bar');
    searchForm?.classList.toggle('show');
};

window.toggleMobileSearchBar = function () {
    const searchForm = document.getElementById('mobile-search-bar');
    searchForm?.classList.toggle('show');
};

window.scrollRecommendations = function (direction) {
    const container = document.getElementById('recommendation-track');
    const card = container?.querySelector('.product-card');
    if (!container || !card) return;

    const scrollAmount = card.offsetWidth + 20;
    container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
};

window.handleSortAndScroll = function () {
    const form = document.getElementById('sort-form');
    if (!form) return;

    localStorage.setItem('scrollToAfterReload', 'product-list');
    form.submit();
};

window.resetSorting = function () {
    const form = document.getElementById('sort-form');
    const sortSelect = document.getElementById('sort');

    if (!form || !sortSelect) return;

    sortSelect.value = "";
    localStorage.setItem('scrollToAfterReload', 'product-list');
    form.submit();
};



document.addEventListener('DOMContentLoaded', () => {
    // Görgetés a keresett terméklistára
    const urlParams = new URLSearchParams(window.location.search);
    const target = document.getElementById('product-list');
    if (urlParams.has('search') && target) {
        target.scrollIntoView({behavior: 'smooth'});
    }
});


// Újratöltés után görgessen le a terméklistához
document.addEventListener('DOMContentLoaded', () => {
    const scrollKey = 'scrollToAfterReload';
    const scrollTargetId = localStorage.getItem(scrollKey);
    if (scrollTargetId) {
        const target = document.getElementById(scrollTargetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
        localStorage.removeItem(scrollKey); // egyszer használatos
    }
});

